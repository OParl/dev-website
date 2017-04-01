<?php

class DownloadTest extends TestCase
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $fs = null;

    public function setUp()
    {
        $this->app = $this->createApplication();

        $this->fs = $this->app->make(\Illuminate\Contracts\Filesystem\Filesystem::class);
    }

    public function testConstruct()
    {
        $sut = new \OParl\Spec\Model\Download($this->fs, 'filename');
        $this->assertEquals('filename', $sut->getFilename());
    }

    public function testGetInfo()
    {
        $filename = '__test_file';
        $this->fs->put($filename, 'Text Content');

        $sut = new \OParl\Spec\Model\Download($this->fs, $filename);

        $info = $sut->getInfo();

        $this->assertInstanceOf(\SplFileInfo::class, $info);
        $this->assertEquals($filename, $info->getFilename());
    }
}