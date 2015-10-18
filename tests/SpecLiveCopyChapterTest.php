<?php

use OParl\Spec\Chapter;

class SpecLiveCopyChapterTest extends TestCase
{
    public function testCreate()
    {
        $fileInfo = $this->getMock(SplFileInfo::class, ['getRealPath'], ['Path']);

        $instance = new Chapter($fileInfo);

        $this->assertInstanceOf(Chapter::class, $instance);
    }

    public function testMethods()
    {
        $fileInfo = new SplFileInfo(storage_path('app/livecopy/src/1-00-einleitung.md'));
        $contents = file_get_contents($fileInfo->getRealPath());

        $instance = new Chapter($fileInfo);

        $this->assertEquals($fileInfo->getBasename(), $instance->getFilename());
        $this->assertTrue(is_string($instance->getRaw()));
        $this->assertEquals($contents, $instance->getRaw());
        $this->assertEquals($contents, strval($instance));
    }
}
