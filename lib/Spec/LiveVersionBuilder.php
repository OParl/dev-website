<?php namespace OParl\Spec;

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
        if (file_exists($liveVersionPath)) {
            $this->html = file_get_contents($liveVersionPath);
        } else {
            throw new FileNotFoundException("Failed to locate live version HTML");
        }

        $this->parseChapters();
        $this->parseHTML();
    }

    public function parseChapters()
    {
        $finder = new Finder();

        $finder->in(LiveVersionRepository::getChapterPath())->name('*.md');

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

        // fix code tags
        $html = preg_replace('/<pre class="json">.*<code.*>/', '<pre><code class="language-javascript">', $html);
    }
}