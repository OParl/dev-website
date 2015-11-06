<?php

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use OParl\Spec\LiveVersionBuilder;
use OParl\Spec\LiveVersionRepository;
use OParl\Spec\LiveVersionUpdater;

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

    }

    public function testLoadWithNonExistingHTML()
    {

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
