<?php

namespace OParl\Spec;

use Debugbar;
use EFrane\Letterpress\Letterpress;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class LiveVersionBuilder
 *
 * Prepare the live version for web display
 *
 * @package OParl\Spec
 */
class LiveVersionBuilder
{
    /**
     * @var Filesystem
     **/
    protected $fs = null;

    /**
     * @var string full live version HTML from `make live`
     **/
    protected $html = '';

    /**
     * @var string extracted content
     **/
    protected $content = '';

    /**
     * @var string extracted navigation
     **/
    protected $nav = '';

    /**
     * @var \Illuminate\Support\Collection list of chapters
     */
    protected $chapters = null;

    /**
     * LiveVersionBuilder constructor.
     *
     * @param Filesystem $fs
     * @param $liveVersionPath
     */
    public function __construct(Filesystem $fs, $liveVersionPath, $autoload = true)
    {
        $this->fs = $fs;

        if ($autoload) {
            $this->load($liveVersionPath);
        }
    }

    /**
     * @param $liveVersionPath
     **/
    public function load($liveVersionPath)
    {
        \Debugbar::startMeasure('liveversionbuilder.load', 'Prepare live version');
        // NOTE: Find out why filesystem sometimes fails to resolve existing files
        if ($this->fs->exists($liveVersionPath)) {
            $this->html = $this->fs->get($liveVersionPath);
        } else {
            throw new FileNotFoundException('Failed to locate live version HTML');
        }

        $this->parseChapters();
        $this->parseHTML();
        \Debugbar::endMeasure('liveversionbuilder.load');
    }

    /**
     * Parse the raw markdown chapters into a collection
     */
    public function parseChapters()
    {
        $finder = new Finder();

        $finder->in(LiveVersionRepository::getChapterPath(true))->name('*.md');

        $files = iterator_to_array($finder);

        $this->chapters = collect($files)->map(function ($f) {
            return file_get_contents($f);
        });
    }

    /**
     * Extract and optimize the html sections for web display
     */
    public function parseHTML()
    {

        $this->extractSections();

        $this->fixNavHTML();
        $this->fixContentHTML();
    }

    /**
     *
     */
    public function extractSections()
    {
        \Debugbar::startMesaser('liveversionbuilder.extractSections', 'Split document');
        $crawler = new Crawler($this->html);

        $navElements = $crawler->filter('body > nav');
        $this->nav = $navElements->html();

        $content = $crawler->filterXPath('//body/*[not(self::nav)]');

        foreach ($content as $domElement) {
            $this->content .= $domElement->ownerDocument->saveHTML($domElement);
        }
        \Debugbar::endMeasure('liveversionbuilder.extractSections');
    }

    /**
     *
     */
    public function fixNavHTML()
    {
        $this->nav = str_replace('href="#', 'href="' . route('specification.index') . '#', $this->nav);
    }

    /**
     *
     */
    public function fixContentHTML()
    {
        \Debugbar::startMeasure('liveversionbuilder.prepareContentHTML', 'Prepare content HTML');
        $html = $this->content;

        // fix image urls
        $html = preg_replace('/"(.?)(images\/.+\.png)"/', '"$1/spezifikation/$2"', $html);

        // fix image tags
        $html = str_replace('<img ', '<img class="img-responsive"', $html);

        // TODO: link oparl:<entity> to #schema-<entity>

        // fix code tags for prism
        $html = $this->fixCodeTag($html, 'json', 'javascript');
        $html = $this->fixCodeTag($html, 'sql');

        // wrap examples into closed-by-default accordions
        $exampleIdentifierCount = 1;
        $html = preg_replace_callback(
            '#<p><strong>(Beispiel.*?)</strong></p>\n(<pre(?:.*?)</pre>)#s',
            $this->transformSchemaCodeExamplesToButtons(), $html
        );

        /* @var $letterpress Letterpress */
        $letterpress = app(Letterpress::class);
        $this->content = $letterpress->typofix($html);

        \Debugbar::endMeasure('liveversionbuilder.prepareContentHTML');
    }

    /**
     * @param $html
     *
     * @return mixed
     **/
    protected function fixCodeTag(&$html, $fromLanguage, $toLanguage = '')
    {
        if ($toLanguage == '') {
            $toLanguage = $fromLanguage;
        }

        $html = preg_replace('/<pre(.+)class="' . $fromLanguage . '">.*?<code.*?>/',
            '<pre$1><code class="language-' . $toLanguage . '">', $html);

        return $html;
    }

    /**
     * @return \Closure
     **/
    protected function transformSchemaCodeExamplesToButtons()
    {
        return function ($match) use (&$exampleIdentifierCount) {
            $data = [
                'exampleIdentifier' => 'example-' . $exampleIdentifierCount,
                'exampleTitle'      => $match[1],
                'exampleCode'       => $match[2],
            ];

            $exampleIdentifierCount++;

            return view('specification.example', $data);
        };
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
     * @return \Illuminate\Support\Collection
     **/
    public function getChapters()
    {
        return $this->chapters;
    }

    /**
     * @return string get the raw markdown version of the concatenated chapters
     **/
    public function getRaw()
    {
        return $this->chapters->reduce(function ($carry, $current) {
            return $carry . $current;
        }, '');
    }
}
