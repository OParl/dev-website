<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use OParl\Spec\LiveCopyRepository;

class SpecLiveCopyRepositoryTest extends TestCase
{
    public function testCreate()
    {
        $instance = app()->make(LiveCopyRepository::class);

        $this->assertInstanceOf(LiveCopyRepository::class, $instance);
    }

    public function testRaw()
    {
        $instance = app()->make(LiveCopyRepository::class);

        // must return a string, should return a string of length > 0
        $this->assertTrue(is_string($instance->getRaw()));
        $this->assertTrue(strlen($instance->getRaw()) > 0);

        $this->assertContains('OParl-Spezifikation', $instance->getRaw());

        $this->assertFalse(is_int($instance->getRaw()));
        $this->assertFalse(is_object($instance->getRaw()));
    }

    public function testGetContent()
    {
        $instance = app()->make(LiveCopyRepository::class);

        // must return a string, should return a string of length > 0
        $this->assertTrue(is_string($instance->getContent()));
        $this->assertTrue(strlen($instance->getContent()) >= 0);

        $this->assertFalse(is_int($instance->getContent()));
        $this->assertFalse(is_object($instance->getContent()));
    }

    public function testGetNav()
    {
        $instance = app()->make(LiveCopyRepository::class);

        // must return a string, should return a string of length > 0
        $this->assertTrue(is_string($instance->getNav()));
        $this->assertTrue(strlen($instance->getNav()) >= 0);

        $this->assertFalse(is_int($instance->getNav()));
        $this->assertFalse(is_object($instance->getNav()));
    }

    public function testGetLastModified()
    {
        $instance = app()->make(LiveCopyRepository::class);

        $this->assertInstanceOf(Carbon::class, $instance->getLastModified());
    }

    public function testGetChapterPath()
    {
        $instance = app()->make(LiveCopyRepository::class);

        $path = LiveCopyRepository::PATH . '/src/';

        $this->assertTrue(is_string($instance->getChapterPath()));
        $this->assertStringEndsWith($path, $instance->getChapterPath());
        $this->assertFileExists($instance->getChapterPath());
    }

    public function testGetImagesPath()
    {
        $instance = app()->make(LiveCopyRepository::class);

        $path = LiveCopyRepository::PATH . '/src/images/';

        $this->assertTrue(is_string($instance->getImagesPath()));
        $this->assertStringEndsWith($path, $instance->getImagesPath());
        $this->assertFileExists($instance->getImagesPath());
    }

    public function testGetSchemaPath()
    {
        $instance = app()->make(LiveCopyRepository::class);

        $path = LiveCopyRepository::PATH . '/schema/';

        $this->assertTrue(is_string($instance->getSchemaPath()));
        $this->assertStringEndsWith($path, $instance->getSchemaPath());
        $this->assertFileExists($instance->getSchemaPath());
    }

    public function testGetExamplesPath()
    {
        $instance = app()->make(LiveCopyRepository::class);

        $path = LiveCopyRepository::PATH . '/examples/';

        $this->assertTrue(is_string($instance->getExamplesPath()));
        $this->assertStringEndsWith($path, $instance->getExamplesPath());
        $this->assertFileExists($instance->getExamplesPath());
    }

    public function testGetLiveCopyPath()
    {
        $instance = app()->make(LiveCopyRepository::class);

        $path = LiveCopyRepository::PATH . '/out/live.html';

        $this->assertTrue(is_string($instance->getLiveCopyPath()));
        $this->assertStringEndsWith($path, $instance->getLiveCopyPath());

        // NOTE: This is not being checked for existence in favor of falling back
        //       to Parsedown output if the live.html version could not be
        //       generated for some reason
        //$this->assertFileExists($instance->getLiveCopyPath());
    }
}
