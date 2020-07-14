<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 24/11/2016
 * Time: 11:21.
 */

namespace App\Model;

use EFrane\Letterpress\Letterpress;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Masterminds\HTML5;
use Psr\Log\LoggerInterface;
use SplFileInfo;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Represents a live view of the specification.
 *
 * Provides the necessary modifications to turn pandoc version into presentable html
 */
class LiveView
{
    protected $fs = null;
    protected $originalHTML = '';
    protected $originalDOM = null;
    protected $versionInformation = [];

    protected $body = '';
    protected $tableOfContents = '';
    protected $loadedVersion = '';

    public function __construct(Filesystem $fs, LoggerInterface $log, $version)
    {
        $this->fs = $fs;
        $this->loadedVersion = $version;

        try {
            $this->originalHTML = $fs->get('live/' . $this->loadedVersion . '/live.html');
            $this->versionInformation = json_decode(
                $fs->get('live/' . $this->loadedVersion . '/version.json'),
                true
            );
        } catch (FileNotFoundException $e) {
            // TODO: send an alert to slack, this is a fatal error and should be solved quickly
            $log->error('Failed to load live version');
        }

        $html5 = new HTML5();
        $this->originalDOM = $html5->loadHTML($this->originalHTML);

        $this->parse();
    }

    protected function parse()
    {
        // split into table of contents and body

        $crawler = new Crawler($this->originalHTML);

        $this->tableOfContents = $crawler->filter('body > nav')->html();
        $this->body = $crawler->filter('body > main')->html();

        $this->reformatTableOfContents();
        $this->reformatBody();
    }

    /**
     * @param string $html
     * @param string $fromLanguage
     * @param string $toLanguage
     *
     * @return mixed
     */
    protected function fixCodeTag(string &$html, string $fromLanguage, string $toLanguage = '')
    {
        if ($toLanguage == '') {
            $toLanguage = $fromLanguage;
        }

        $html = preg_replace('/<pre(.+)class="'.$fromLanguage.'">.*?<code.*?>/',
            '<pre$1><code class="language-'.$toLanguage.'">', $html);

        return $html;
    }

    /**
     * @return \Closure
     **/
    protected function transformSchemaCodeExamplesToButtons()
    {
        return function ($match) use (&$exampleIdentifierCount) {
            $data = [
                'exampleIdentifier' => 'example-'.$exampleIdentifierCount,
                'exampleTitle'      => $match[1],
                'exampleCode'       => $match[2],
            ];

            $exampleIdentifierCount++;

            return view('specification.example', $data);
        };
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getTableOfContents()
    {
        return $this->tableOfContents;
    }

    public function getOfficialVersion(): string
    {
        return $this->versionInformation['official'];
    }

    public function getGitHash(): string
    {
        return $this->versionInformation['hash'];
    }

    public function hasImage($image): bool
    {
        $path = $this->formatImagePath($image);

        return $this->fs->exists($path);
    }

    /**
     * @param string $imagePath
     * @return string
     */
    protected function formatImagePath(string $imagePath): string
    {
        return 'live/'.$this->loadedVersion.'/images/'.$imagePath.'.png';
    }

    public function getImage($image)
    {
        $imagePath = $this->formatImagePath($image);

        return new SplFileInfo(storage_path('app/'.$imagePath));
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function getRaw()
    {
        return $this->fs->get('live/' . $this->loadedVersion . '/raw.md');
    }

    protected function reformatBody(): void
    {
        // rewrite image urls
        $this->body = preg_replace(
            '/"(.??)(.*images\/)(.+\.png)"/',
            '"$1/spezifikation/'.$this->loadedVersion.
            '/images/$3"',
            $this->body
        );

        // fix image tags
        $this->body = str_replace('<img ', '<img class="img-responsive"', $this->body);

        // fix table tags
        $this->body = str_replace(
            '<table>',
            '<table class="table table-striped table-condensed table-responsive">',
            $this->body
        );
        // fix code tags for prism

        $this->body = $this->fixCodeTag($this->body, 'json', 'javascript');

        $this->body = $this->fixCodeTag($this->body, 'sql');
        // wrap examples into closed-by-default accordions

        $exampleIdentifierCount = 1;
        $this->body = preg_replace_callback(
            '#<p><strong>(Beispiel.*?)</strong></p>\n(<pre(?:.*?)</pre>)#s',
            $this->transformSchemaCodeExamplesToButtons(),
            $this->body
        );

        /* @var $letterpress Letterpress */
        $letterpress = app(Letterpress::class);
        $this->body = $letterpress->typofix($this->body);

        // route for the following link rewrites
        $route = route('specification.index', ['version' => $this->getOfficialVersion()]);

        // fix footnote links
        $this->body = str_replace('class="footnotes"', 'class="c-footnotes"', $this->body);
        $this->body = preg_replace('/#fn(ref)?\d/', "{$route}/$0", $this->body);
    }

    /**
     * The table of content is a set of nested ul>li>ul lists
     */
    protected function reformatTableOfContents()
    {
        $tableOfContentsXML = simplexml_load_string($this->tableOfContents);

        $reformatted = null;

        foreach ($tableOfContentsXML->li as $section) {
            $title = $section->a->asXML();

            $headings = [];
            foreach ($section->ul->li as $heading) {
                $headings[] = $heading->a->asXML();
            }

            $reformatted .= view('specification.toc.section', compact('title', 'headings'));
        }

        $this->tableOfContents = $reformatted;
    }
}
