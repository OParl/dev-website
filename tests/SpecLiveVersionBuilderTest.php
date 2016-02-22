<?php

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use OParl\Spec\LiveVersionBuilder;
use OParl\Spec\LiveVersionRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class SpecLiveVersionBuilderTest extends TestCase
{
    public function testCreate()
    {
        $fs = app(Filesystem::class);
        $liveVersionPath = LiveVersionRepository::getLiveVersionPath();

        $instance = new LiveVersionBuilder($fs, $liveVersionPath);

        $this->assertInstanceOf(LiveVersionBuilder::class, $instance);
    }

    public function testGetContent()
    {
        $instance = app(LiveVersionBuilder::class);

        $content = $instance->getContent();

        $this->assertTrue(is_string($content));
    }

    public function testGetNav()
    {
        $instance = app(LiveVersionBuilder::class);

        $nav = $instance->getNav();

        $this->assertTrue(is_string($nav));
    }

    public function testGetChapters()
    {
        $instance = app(LiveVersionBuilder::class);

        $chapters = $instance->getChapters();

        $this->assertInstanceOf(Collection::class, $chapters);
    }

    public function testLoad()
    {
        /* @var $builderMock LiveVersionBuilder|PHPUnit_Framework_MockObject_MockObject */
        $builderMock = $this->getMock(LiveVersionBuilder::class, [
            'parseChapters',
            'parseHTML'
        ], [
            app(Filesystem::class),
            ''
        ]);

        $builderMock->expects($this->once())->method('parseChapters');
        $builderMock->expects($this->once())->method('parseHTML');

        $builderMock->load(LiveVersionRepository::getLiveVersionPath());
    }

    public function testLoadWithNonExistingHTML()
    {
        $this->setExpectedException(FileNotFoundException::class);

        app(LiveVersionBuilder::class)->load('file_not_found');
    }

    public function testParseChapters()
    {

    }

    public function testParseHTML()
    {

    }

    public function testExtractSections()
    {

    }

    public function testFixNavHTML()
    {

    }

    public function testFixContentHTML()
    {

    }
}
