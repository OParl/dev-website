<?php namespace OParl\Spec;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Pandoc\Pandoc;
use Symfony\Component\DomCrawler\Crawler;

class LiveCopyRepository
{
  const PATH = 'oparl-json-schema-master';

  /**
   * @var \Illuminate\Support\Collection
   **/
  protected $chapters = null;

  protected $content = '';
  protected $nav = '';

  public function __construct(Filesystem $fs, CacheRepository $cache)
  {
    $this->chapters = $cache->rememberForever('livecopy:chapters',
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

    $this->parse($cache);
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

  protected function parseMarkdown($markdown)
  {
    // transform with pandoc
    $pandoc = new Pandoc();

    $html = $pandoc->runWith($markdown, [
      'number-sections' => null,
      'section-divs' => null,
      'table-of-contents' => null,
      'standalone' => null,
      'from' => 'markdown',
      'to' => 'html5',
      'toc-depth' => 2,
      'no-highlight' => null,
    ]);

    return $html;
  }

  /**
   * @return string
   **/
  protected function parse(CacheRepository $cache)
  {
    $markdown = $this->getRaw();

    $html = $cache->rememberForever('livecopy:html', function () use ($markdown) {
      return $this->parseMarkdown($markdown);
    });

    $crawler = new Crawler($html);
    $this->nav = $crawler->filter('body > nav')->html();

    $content = $crawler->filterXPath("//body/*[not(self::nav)]");
    foreach ($content as $domElement)
      $this->content .= $domElement->ownerDocument->saveHTML($domElement);

    $this->fixHTML();
  }


  protected function fixHTML()
  {
    // fix image urls
    $this->content = preg_replace('/"(.?)(images\/.+\.png)"/', '"$1/spezifikation/$2"', $this->content);

    // fix image tags
    $this->content = str_replace('<img ', '<img class="img-responsive"', $this->content);

    // fix table tags
    $this->content = str_replace('<table>', '<table class="table table-striped table-condensed table-responsive">', $this->content);

    // fix code tags
    $this->content = str_replace('<code>', '<code class="language-javascript">', $this->content);
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
}