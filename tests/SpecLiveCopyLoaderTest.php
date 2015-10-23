<?php

use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\LiveCopyLoader;

class SpecLiveCopyLoaderTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $fs = $this->getMock(Filesystem::class);

        $instance = new LiveCopyLoader($fs, '');

        $this->assertInstanceOf(LiveCopyLoader::class, $instance);
    }

    public function testRepositoryExistsWithExistingPath()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveCopyLoader($fs, __DIR__);

        $this->assertTrue($instance->repositoryExists());
    }

    public function testRepositoryExistsWithNonexistingPath()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveCopyLoader($fs, '__DIR__');

        $this->assertFalse($instance->repositoryExists());
    }

    public function testUpdateRepositoryDefaultWithExistingDir()
    {
        $fs = app(Filesystem::class);

        $instance = $this->getMock(LiveCopyLoader::class, ['rebaseRepository'], [$fs, __DIR__]);
        $instance->expects($this->once())->method('rebaseRepository');

        $instance->updateRepository();
    }

    public function testUpdateRepositoryDefaultWithNonExistingDir()
    {
        $fs = app(Filesystem::class);

        $instance = $this->getMock(LiveCopyLoader::class, ['cloneRepository'], [$fs, '__DIR__']);
        $instance->expects($this->once())->method('cloneRepository');

        $instance->updateRepository();
    }

    public function testUpdateRepositoryForceWithExistingDir()
    {
        $fs = app(Filesystem::class);

        $instance = $this->getMock(LiveCopyLoader::class, ['cloneRepository'], [$fs, __DIR__]);
        $instance->expects($this->once())->method('cloneRepository');

        $instance->updateRepository(true);
    }

    public function testCloneRepositoryDryRun()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveCopyLoader($fs, '__DIR__');

        $this->assertEquals(-1, $instance->cloneRepository(true));
        $this->assertFileNotExists(storage_path('app/__DIR__'));
    }

    public function testCloneRepositoryShouldClone()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveCopyLoader($fs, 'testclone');

        $this->assertEquals(0, $instance->cloneRepository());
        $this->assertFileExists(storage_path('app/testclone'));
        $this->assertTrue($instance->repositoryExists(), 'Failed asserting that the repository exists.');
    }

    /**
     * @depends testCloneRepositoryShouldClone
     */
    public function testRebaseRepositoryDefaultWithExistingDir()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveCopyLoader($fs, storage_path('app/testclone'));

        $this->assertEquals(0, $instance->rebaseRepository());
    }

    /**
     * @depends testRebaseRepositoryDefaultWithExistingDir
     */
    public function testRemovesRepository()
    {
        $fs = app(Filesystem::class);

        $instance = new LiveCopyLoader($fs, storage_path('app/testclone'));

        if (!$instance->repositoryExists())
        {
            $instance->updateRepository();
        }

        $this->assertTrue($instance->deleteRepository());
        $this->assertFileNotExists(storage_path('app/testclone'));
    }
}