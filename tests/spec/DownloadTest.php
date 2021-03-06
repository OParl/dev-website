<?php

class DownloadTest extends TestCase
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $fs = null;

    public function setUp(): void
    {
        $this->app = $this->createApplication();

        $this->fs = $this->app->make(\Illuminate\Contracts\Filesystem\Filesystem::class);
    }

    public function testGetFilename()
    {
        $sut = new \App\Model\Download($this->fs, 'filename');
        $this->assertEquals('filename', $sut->getFilename());
    }

    public function testGetInfo()
    {
        $filename = '__test_file';
        $this->fs->put($filename, '');

        $sut = new \App\Model\Download($this->fs, $filename);

        $info = $sut->getInfo();

        $this->assertInstanceOf(\SplFileInfo::class, $info);
        $this->assertEquals($filename, $info->getFilename());
        $this->assertEquals(0, $info->getSize());
    }

    public function testGetData()
    {
        $filename = '__test_file';
        $content = 'Content';

        $this->fs->put($filename, $content);

        $sut = new \App\Model\Download($this->fs, $filename);

        $this->assertEquals($content, $sut->getData());
    }

    public function testToString()
    {
        $sut = new \App\Model\Download($this->fs, 'filename');
        $this->assertEquals('filename', (string) $sut);
    }
}
