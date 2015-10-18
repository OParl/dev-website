<?php namespace OParl\Spec;

use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\Finder;

/**
 * Class LiveCopyRepository
 * @package OParl\Spec
 **/
class LiveCopyRepository
{
    /**
   *
   */
  const PATH = 'livecopy';

    /**
     * @var \Illuminate\Support\Collection
     **/
    protected $chapters = null;

  /**
   * @var string
   **/
  protected $content = '';
  /**
   * @var string
   **/
  protected $nav = '';
  /**
   * @var string
   **/
  protected $hash = '';

  /**
   * @var CacheRepository|null
   **/
  protected $cache = null;
  /**
   * @var Filesystem|null
   **/
  protected $fs = null;

  /**
   * @param Filesystem $fs
   * @param CacheRepository $cache
   */
  public function __construct(Filesystem $fs, CacheRepository $cache)
  {
      $this->fs = $fs;
      $this->cache = $cache;

      // FIXME: While this is working as expected, it kind of should not
      //        since this is supposed to run if the chapter path exists
      if (!$fs->exists(static::getChapterPath()))
      {
        $this->loadChapters($cache);
      }

      // FIXME: While this is working as expected, it kind of should not
      //        since this is supposed to run if the live copy path does not exist
      if ($fs->exists(static::getLiveCopyPath())) {
          $this->buildLiveCopy();
      }

      $this->parse($fs, $cache);
  }

  /**
   * @return mixed
   **/
  public function getRaw()
  {
      if (is_null($this->chapters)) return '';

      return $this->chapters->reduce(function ($carry, Chapter $chapter) {
            return $carry . "\n\n" . $chapter;
        }, '');
  }

  /**
   * @return string
   **/
  public function getContent()
  {
      return $this->content;
  }

  /**
   * @return string
   **/
  public function getNav()
  {
      return $this->nav;
  }

  /**
   * @return static
   **/
  public function getLastModified()
  {
      try {
          $unixTime = $this->fs->lastModified($this->getLiveCopyPath());
          return Carbon::createFromTimestamp($unixTime);
      } catch (\Exception $e) {
          return Carbon::createFromDate(1999, 1, 1);
      }
  }

  /**
   * @return mixed|string
   */
  protected function buildLiveCopy()
  {
      if ($this->fs->exists(static::getLiveCopyPath())) {
          $html = $this->fs->get(static::getLiveCopyPath());
      } else {
          // fallback to using Parsedown
          $markdown = $this->getRaw();
          $html = \Parsedown::instance()->parse($markdown);
      }

      return $html;
  }

    /**
     * @return string
     **/
    protected function parse(Filesystem $fs, CacheRepository $cache)
    {
        $html = $cache->remember('livecopy:raw_html', 60, function () use ($fs) {
            return $this->buildLiveCopy();
        });

        $this->extractNav($html);
        $this->extractContent($html);

        $this->extractHash($cache);

        $this->fixHTML();
    }

  /**
   *
   */
  protected function fixHTML()
  {
      $this->fixContentHTML();
      $this->fixNavHTML();
  }

  /**
   * @return string
   **/
  public static function getChapterPath()
  {
      return storage_path('app/' . LiveCopyRepository::PATH . '/src/');
  }

  /**
   * @return string
   **/
  public static function getImagesPath()
  {
      return storage_path('app/' . LiveCopyRepository::PATH . '/src/images/');
  }

  /**
   * @return string
   **/
  public static function getSchemaPath()
  {
      return storage_path('app/' . LiveCopyRepository::PATH . '/schema/');
  }

  /**
   * @return string
   **/
  public static function getExamplesPath()
  {
      return storage_path('app/' . LiveCopyRepository::PATH . '/examples/');
  }

  /**
   * @return string
   **/
  public static function getLiveCopyPath()
  {
      return storage_path('app/' . LiveCopyRepository::PATH . '/out/live.html');
  }

  /**
   * @param $user
   * @param $repository
   * @param bool|false $forceClone
   **/
  public function refresh($user, $repository, $forceClone = false)
  {
      $this->clearCache();

      $path = storage_path('app/' . self::PATH);

      ($forceClone || !$this->fs->exists(self::PATH))
      ? $this->performCloneRefresh($user, $repository)
      : $this->performPullRefresh($path);

      $this->make();
  }

  /**
   * @param $user
   * @param $repository
   **/
  protected function performCloneRefresh($user, $repository)
  {
      $this->fs->deleteDirectory(self::PATH);

      $gitURL = sprintf("https://github.com/%s/%s", $user, $repository);

      $this->runInDir(storage_path('app'), "git clone --depth=1 {$gitURL} " . self::PATH);
  }

  /**
   * @param $path
   **/
  protected function performPullRefresh($path)
  {
      $this->runInDir($path, 'git pull --rebase');
  }

  /**
   *
   */
  protected function make()
  {
      $dir = storage_path('app/' . self::PATH);

      $this->runInDir($dir, 'make live');
  }

  /**
   * @return string
   **/
  public function getHash()
  {
      return $this->hash;
  }

  /**
   * @param CacheRepository $cache
   * @internal param Filesystem $fs
   */
  protected function loadChapters(CacheRepository $cache)
  {
      $this->chapters = $cache->remember(
          'livecopy:chapters',
          240,

          function () {
              $finder = new Finder();

              $finder->in($this::getChapterPath())->name('*.md');

              $files = iterator_to_array($finder);

              // FIXME: This is related to a bug in Parsedown that makes it behave
              //        in a weird way when confronted with Pandoc preambles.
              // array_shift($files);

              $files = collect($files)->map(function ($f) {
                  return new Chapter($f);
              });

              return $files;
          }
      );
  }

  /**
   * Clear the cached data
   */
  public function clearCache()
  {
      $this->cache->forget('livecopy:chapters');
      $this->cache->forget('livecopy:raw_html');
      $this->cache->forget('livecopy:hash');
  }

  /**
   * Run a command (or function) in a given working dir
   *
   * Changes to the given dir, runs `$cmd`
   * and returns to the previous working
   * directory thus ensuring that commands always exit
   * into a clean environment.
   *
   * NOTE: If `$cmd` is a string, it will be passed on to `exec()` AS IS.
   *
   * @param string $dir
   * @param \Closure|callable|string $cmd
   * @return string
   **/
  protected function runInDir($dir, $cmd)
  {
      $cwd = getcwd();

      chdir($dir);

      $res = null;
      if (is_array($cmd)) {
          $res = call_user_func($cmd);
      } elseif (is_callable($cmd)) {
          $res = $cmd();
      } else {
          $res = exec($cmd);
      }

      chdir($cwd);

      return $res;
  }

  /**
   * @param $html string
   * @return void
   **/
  protected function extractNav($html)
  {
      $crawler = new Crawler($html);

      try {
          $navElements = $crawler->filter('body > nav');
          $this->nav = $navElements->html();
      } catch (\InvalidArgumentException $e) {
          $this->nav = '';
      }
  }

  /**
   * @param $html string
   * @return void
   **/
  protected function extractContent($html)
  {
      $crawler = new Crawler($html);

      $content = $crawler->filterXPath("//body/*[not(self::nav)]");

      foreach ($content as $domElement) {
          $this->content .= $domElement->ownerDocument->saveHTML($domElement);
      }
  }

  /**
   * @param CacheRepository $cache
   **/
  protected function extractHash(CacheRepository $cache)
  {
      $this->hash = $cache->rememberForever('livecopy:hash', function () {

        try
        {
          $hash = $this->runInDir(
            storage_path('app/' . static::PATH),
            'git show HEAD --format="%H" | head -n1'
          );
        } catch (\ErrorException $e)
        {
          $hash = '<unknown>';
        }


      $hash = trim($hash);

      return $hash;
    });
  }

  /**
   *
   */
  protected function fixContentHTML()
  {
      // fix image urls
      $this->content = preg_replace('/"(.?)(images\/.+\.png)"/', '"$1/spezifikation/$2"', $this->content);

      // fix image tags
      $this->content = str_replace('<img ', '<img class="img-responsive"', $this->content);

      // fix table tags
      $this->content = str_replace('<table>', '<table class="table table-striped table-condensed table-responsive">', $this->content);

      // fix code tags
      $this->content = preg_replace('/<pre class="json">.*<code.*>/', '<pre><code class="language-javascript">', $this->content);
  }

  /**
   *
   */
  protected function fixNavHTML()
  {
      $this->nav = str_replace('<ul>', '<ul class="nav">', $this->nav);
  }
}
