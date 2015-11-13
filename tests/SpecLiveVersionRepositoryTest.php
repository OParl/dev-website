<?php

use Carbon\Carbon;
use OParl\Spec\LiveVersionRepository;

class SpecLiveVersionRepositoryTest extends TestCase
{
    public function testCreate()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $this->assertInstanceOf(LiveVersionRepository::class, $instance);
    }

    public function testGetChapterPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/src/';

        $this->assertTrue(is_string($instance->getChapterPath()));
        $this->assertStringEndsWith($path, $instance->getChapterPath());
        $this->assertFileExists($instance->getChapterPath(true));
    }

    public function testGetImagesPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/src/images/';

        $this->assertTrue(is_string($instance->getImagesPath()));
        $this->assertStringEndsWith($path, $instance->getImagesPath());
        $this->assertFileExists($instance->getImagesPath('', true));
    }

    public function testGetSchemaPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/schema/';

        $this->assertTrue(is_string($instance->getSchemaPath()));
        $this->assertStringEndsWith($path, $instance->getSchemaPath());
        $this->assertFileExists($instance->getSchemaPath('', true));
    }

    public function testGetExamplesPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/examples/';

        $this->assertTrue(is_string($instance->getExamplesPath()));
        $this->assertStringEndsWith($path, $instance->getExamplesPath());
        $this->assertFileExists($instance->getExamplesPath('', true));
    }

    public function testGetLiveCopyPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/out/live.html';

        $this->assertTrue(is_string($instance->getLiveVersionPath()));
        $this->assertStringEndsWith($path, $instance->getLiveVersionPath());
        $this->assertFileExists($instance->getLiveVersionPath(true));
    }
}
