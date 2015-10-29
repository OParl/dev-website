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

    public function testRaw()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $this->assertTrue(is_string($instance->getRaw()));
        $this->assertTrue(strlen($instance->getRaw()) > 0, 'Failed asserting that the raw output has a string length > 0.');

        $this->assertContains('OParl-Spezifikation', $instance->getRaw());

        $this->assertFalse(is_int($instance->getRaw()));
        $this->assertFalse(is_object($instance->getRaw()));
    }

    public function testGetContent()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $this->assertTrue(is_string($instance->getContent()));
        $this->assertTrue(strlen($instance->getContent()) >= 0);

        $this->assertFalse(is_int($instance->getContent()));
        $this->assertFalse(is_object($instance->getContent()));
    }

    public function testGetNav()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $this->assertTrue(is_string($instance->getNav()));
        $this->assertTrue(strlen($instance->getNav()) >= 0);

        $this->assertFalse(is_int($instance->getNav()));
        $this->assertFalse(is_object($instance->getNav()));
    }

    public function testGetLastModified()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $this->assertInstanceOf(Carbon::class, $instance->getLastModified());
    }

    public function testGetChapterPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/src/';

        $this->assertTrue(is_string($instance->getChapterPath()));
        $this->assertStringEndsWith($path, $instance->getChapterPath());
        $this->assertFileExists($instance->getChapterPath());
    }

    public function testGetImagesPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/src/images/';

        $this->assertTrue(is_string($instance->getImagesPath()));
        $this->assertStringEndsWith($path, $instance->getImagesPath());
        $this->assertFileExists($instance->getImagesPath());
    }

    public function testGetSchemaPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/schema/';

        $this->assertTrue(is_string($instance->getSchemaPath()));
        $this->assertStringEndsWith($path, $instance->getSchemaPath());
        $this->assertFileExists($instance->getSchemaPath());
    }

    public function testGetExamplesPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/examples/';

        $this->assertTrue(is_string($instance->getExamplesPath()));
        $this->assertStringEndsWith($path, $instance->getExamplesPath());
        $this->assertFileExists($instance->getExamplesPath());
    }

    public function testGetLiveCopyPath()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $path = LiveVersionRepository::PATH . '/out/live.html';

        $this->assertTrue(is_string($instance->getLiveVersionPath()));
        $this->assertStringEndsWith($path, $instance->getLiveVersionPath());
        $this->assertFileExists($instance->getLiveVersionPath());
    }

    public function testGetHash()
    {
        /* @var $instance LiveVersionRepository */
        $instance = app(LiveVersionRepository::class);

        $this->assertTrue(is_string($instance->getHash()));
        $this->assertRegExp('/([a-z0-9]{40}|<unknown>)/', $instance->getHash());
    }

    public function testClearCache()
    {
        // TODO: This test requires cache facade mocking which appears not to be working as of L5.1
    }

    public function testRefresh()
    {
        // TODO: Not sure on how to test this
    }
}
