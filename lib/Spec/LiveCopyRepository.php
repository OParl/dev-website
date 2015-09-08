<?php namespace OParl\Spec;

use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\DomCrawler\Crawler;

class LiveCopyRepository
{
  const PATH = 'livecopy';

  /**
   * @var \Illuminate\Support\Collection
   **/
  protected $chapters = null;

  protected $content = '';
  protected $nav = '';
  protected $hash = '';

  protected $cache = null;
  protected $fs = null;

  public function __construct(Filesystem $fs, CacheRepository $cache)
  {
    $this->fs = $fs;
    $this->cache = $cache;

    if ($fs->exists($this->getLiveCopyPath()) && $fs->exists($this->getChapterPath()))
    {
      $this->loadChapters($fs, $cache);
      $this->parse($cache, $fs);
    }
  }

  public function getRaw()
  {
    return $this->chapters->reduce(function ($carry, Chapter $chapter) {
      return $carry . "\n" . $chapter->getRaw();
    }, '');
  }

  /**
   * @return string
   */
  public function getContent()
  {
    return $this->content;
  }

  /**
   * @return string
   */
  public function getNav()
  {
    return $this->nav;
  }

  public function getLastModified()
  {
    $unixTime = $this->fs->lastModified($this->getLiveCopyPath());
    return Carbon::createFromTimestamp($unixTime);
  }

  protected function buildLiveCopy(Filesystem $fs)
  {
    $html = $fs->get(static::getLiveCopyPath());
    return $html;
  }

  /**
   * @return string
   **/
  protected function parse(CacheRepository $cache, Filesystem $fs)
  {
    $html = $cache->remember('livecopy:raw_html', 60, function () use ($fs) {
      return $this->buildLiveCopy($fs);
    });

    $crawler = new Crawler($html);
    try
    {
      $navElements = $crawler->filter('body > nav');
      $this->nav = $navElements->html();
    } catch (\InvalidArgumentException $e)
    {
      $this->nav = '';
    }

    $content = $crawler->filterXPath("//body/*[not(self::nav)]");
    foreach ($content as $domElement)
      $this->content .= $domElement->ownerDocument->saveHTML($domElement);

    $this->fixHTML($this->content, $this->nav);

    $this->hash = $cache->rememberForever('livecopy:hash', function () {
      $hash = $this->runInDir('app/'.static::PATH, 'git show HEAD --format="%H" | head -n1');
      $hash = trim($hash);

      return $hash;
    });
  }

  protected function fixHTML(&$content, &$nav)
  {
    // fix image urls
    $content = preg_replace('/"(.?)(images\/.+\.png)"/', '"$1/spezifikation/$2"', $content);

    // fix image tags
    $content = str_replace('<img ', '<img class="img-responsive"', $content);

    // fix table tags
    $content = str_replace('<table>', '<table class="table table-striped table-condensed table-responsive">', $content);

    // fix code tags
    $content = preg_replace('/<pre class="json">.*<code.*>/', '<pre><code class="language-javascript">', $content);

    $nav = str_replace('<ul>', '<ul class="nav">', $nav);
  }

  public static function getChapterPath()
  {
    return LiveCopyRepository::PATH . '/src/';
  }

  public static function getImagesPath()
  {
    return LiveCopyRepository::PATH . '/src/images/';
  }

  public static function getSchemaPath()
  {
    return LiveCopyRepository::PATH . '/schema/';
  }

  public static function getExamplesPath()
  {
    return LiveCopyRepository::PATH . '/examples/';
  }

  public static function getLiveCopyPath()
  {
    return LiveCopyRepository::PATH . '/out/live.html';
  }

  public function refresh($user, $repository, $forceClone = false)
  {
    $this->clearCache();

    $path = storage_path('app/' . self::PATH);

    ($forceClone || !$this->fs->exists($path))
      ? $this->performCloneRefresh($user, $repository)
      : $this->performPullRefresh($path);

    exec('make live');
  }

  /**
   * @param Filesystem $fs
   **/
  protected function performCloneRefresh($user, $repository)
  {
    $this->fs->deleteDirectory(self::PATH);

    $gitURL = sprintf("https://github.com/%s/%s", $user, $repository);

    $this->runInDir(storage_path('app'), "git clone --depth=1 {$gitURL} " . self::PATH);
    $this->runInDir(storage_path('app/' . self::PATH), [&$this, 'make']);
  }

  protected function performPullRefresh($path)
  {
    $this->runInDir($path, 'git pull --rebase');
    $this->runInDir(storage_path('app/' . self::PATH), [&$this, 'make']);
  }

  protected function make()
  {
    exec('make live');
  }

  public function getHash()
  {
    return $this->hash;
  }

  /**
   * @param Filesystem $fs
   * @param CacheRepository $cache
   **/
  protected function loadChapters(Filesystem $fs, CacheRepository $cache)
  {
    $this->chapters = $cache->remember(
      'livecopy:chapters',
      240,
      function () use ($fs) {
        $files = collect($fs->allFiles($this->getChapterPath()))->sort();
        $files->shift();

        return $files->filter(function ($file) {
          return ends_with($file, '.md');
        })->map(function ($chapterFile) use ($fs) {
          return new Chapter($fs, $chapterFile);
        });
      }
    );
  }

  /**
   * Clear the cached data
   */
  protected function clearCache()
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
    if (is_array($cmd))
    {
      $res = call_user_func($cmd);
    } else if (is_callable($cmd))
    {
      $res = $cmd();
    } else
    {
      $res = exec($cmd);
    }

    chdir($cwd);

    return $res;
  }
}