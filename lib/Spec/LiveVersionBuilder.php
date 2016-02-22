<?php namespace OParl\Spec;

use EFrane\Letterpress\Letterpress;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class LiveVersionBuilder
{
    protected $fs = null;

    protected $html = '';

    protected $content = '';

    protected $nav = '';

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $chapters = null;

    public function __construct(Filesystem $fs, $liveVersionPath)
    {
        $this->fs = $fs;

        $this->load($liveVersionPath);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getNav()
    {
        return $this->nav;
    }

    public function getChapters()
    {
        return $this->chapters;
    }

    public function getRaw()
    {
        return $this->chapters->reduce(function ($carry, $current) {
            return $carry . $current;
        }, '');
    }

    public function load($liveVersionPath)
    {
        // NOTE: Find out why filesystem sometimes fails to resolve existing files
        if ($this->fs->exists($liveVersionPath)) {
            $this->html = $this->fs->get($liveVersionPath);
        } else {
            throw new FileNotFoundException("Failed to locate live version HTML");
        }

        $this->parseChapters();
        $this->parseHTML();
    }

    public function parseChapters()
    {
        $finder = new Finder();

        $finder->in(LiveVersionRepository::getChapterPath(true))->name('*.md');

        $files = iterator_to_array($finder);

        $this->chapters = collect($files)->map(function ($f) {
            return file_get_contents($f);
        });
    }

    public function parseHTML()
    {
        $this->extractSections();

        $this->fixNavHTML($this->nav);
        $this->fixContentHTML($this->content);

        /* @var $letterpress Letterpress */
        $letterpress = app(Letterpress::class);

        $this->content = $letterpress->press($this->content);
    }

    public function extractSections()
    {
        $crawler = new Crawler($this->html);

        $navElements = $crawler->filter('body > nav');
        $this->nav = $navElements->html();

        $content = $crawler->filterXPath("//body/*[not(self::nav)]");

        foreach ($content as $domElement) {
            $this->content .= $domElement->ownerDocument->saveHTML($domElement);
        }
    }

    public function fixNavHTML(&$html)
    {
        $html = str_replace('<ul>', '<ul class="nav">', $html);
    }

    public function fixContentHTML(&$html)
    {
        // fix image urls
        $html = preg_replace('/"(.?)(images\/.+\.png)"/', '"$1/spezifikation/$2"', $html);

        // fix image tags
        $html = str_replace('<img ', '<img class="img-responsive"', $html);

        // fix table tags
        $html = str_replace('<table>', '<table class="table table-striped table-condensed table-responsive">', $html);

        // fix code tags for prism
        $html = $this->fixCodeTag($html, 'json', 'javascript');
        $html = $this->fixCodeTag($html, 'sql');

        // wrap examples into closed-by-default accordions
        $exampleIdentifierCount = 1;
        $html = preg_replace_callback(
            '#<p><strong>(Beispiel.*?)</strong></p>\n(<pre(?:.*?)</pre>)#s',
            function ($match) use (&$exampleIdentifierCount) {
                $data = [
                    'exampleIdentifier' => 'example-' . $exampleIdentifierCount,
                    'exampleTitle' => $match[1],
                    'exampleCode' => $match[2]
                ];

                $exampleIdentifierCount++;

                return view('specification.example', $data);
            }, $html
        );
    }

    /**
     * @param $html
     * @return mixed
     **/
    protected function fixCodeTag(&$html, $fromLanguage, $toLanguage = '')
    {
        if ($toLanguage == '') $toLanguage = $fromLanguage;

        $html = preg_replace('/<pre(.+)class="'.$fromLanguage.'">.*?<code.*?>/', '<pre$1><code class="language-'.$toLanguage.'">', $html);
        return $html;
    }
}