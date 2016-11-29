<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 24/11/2016
 * Time: 11:21
 */

namespace OParl\Spec\Model;

use Illuminate\Contracts\Filesystem\Filesystem;
use Masterminds\HTML5;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Represents a live view of the specification
 *
 * Provides the necessary modifications to turn
 *
 * @package OParl\Spec\Model
 */
class LiveView
{
    protected $fs = null;
    protected $originalHTML = '';
    protected $originalDOM = null;
    protected $versionInformation = [];

    protected $body = '';
    protected $tableOfContents = '';

    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;

        $this->originalHTML = $fs->get('live/live.html');
        $this->versionInformation = json_decode($fs->get('live/version.json'), true);

        $html5 = new HTML5();
        $this->originalDOM = $html5->loadHTML($this->originalHTML);

        $this->parse();
    }

    protected function parse()
    {
        // split into table of contents and body

        $crawler = new Crawler($this->originalHTML);

        $this->tableOfContents = $crawler->filter('body > nav')->html();

        $content = $crawler->filterXPath('//body/*[not(self::nav)]');
        foreach ($content as $domElement) {
            $this->body .= $domElement->ownerDocument->saveHTML($domElement);
        }

        // rewrite image urls

        $this->body = preg_replace('/"(.?)(src\/images\/)(.+\.png)"/', '"$1/spezifikation/images/$3"', $this->body);

        // rewrite examples
        // rewrite footnotes

    }

    public function getBody()
    {
        return $this->body;
    }

    public function getTableOfContents()
    {
        return $this->tableOfContents;
    }

    public function getVersionInformation()
    {
        return $this->versionInformation;
    }

    public function getImage($imagePath)
    {
        $path = "live/images/{$imagePath}.png";

        $data = null;

        if ($this->fs->exists($path)) {
            $data = $this->fs->get($path);
        }

        return $data;
    }
}