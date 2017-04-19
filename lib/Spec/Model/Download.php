<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 23/11/2016
 * Time: 10:57.
 */

namespace OParl\Spec\Model;

use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Class Download.
 */
class Download
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var string
     */
    protected $filename;

    public function __construct(Filesystem $fs, $filename)
    {
        $this->fs = $fs;
        $this->filename = $filename;
    }

    /**
     * @return \SplFileInfo
     */
    public function getInfo()
    {
        return new \SplFileInfo(storage_path("app/{$this->filename}"));
    }

    public function getData()
    {
        return $this->fs->get($this->filename);
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function __toString()
    {
        return $this->getFilename();
    }
}
