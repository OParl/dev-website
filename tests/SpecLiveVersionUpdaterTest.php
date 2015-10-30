<?php

use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\Mock;
use OParl\Spec\LiveVersionUpdater;

class SpecLiveVersionUpdaterTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $fs = $this->getMock(Filesystem::class);

        $instance = new LiveVersionUpdater($fs, '');

        $this->assertInstanceOf(LiveVersionUpdater::class, $instance);
    }

    public function testRepositoryExistsWithExistingPath()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveVersionUpdater($fs, __DIR__);

        $this->assertTrue($instance->repositoryExists());
    }

    public function testRepositoryExistsWithNonexistingPath()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveVersionUpdater($fs, '__DIR__');

        $this->assertFalse($instance->repositoryExists());
    }

    public function testUpdateRepositoryDefaultWithExistingDir()
    {
        $fs = app(Filesystem::class);

        $instance = $this->getMock(LiveVersionUpdater::class, ['rebaseRepository'], [$fs, __DIR__]);
        $instance->expects($this->once())->method('rebaseRepository');

        $instance->updateRepository();
    }

    public function testUpdateRepositoryDefaultWithNonExistingDir()
    {
        $fs = app(Filesystem::class);

        $instance = $this->getMock(LiveVersionUpdater::class, ['cloneRepository'], [$fs, '__DIR__']);
        $instance->expects($this->once())->method('cloneRepository');

        $instance->updateRepository();
    }

    public function testUpdateRepositoryForceWithExistingDir()
    {
        $fs = app(Filesystem::class);

        $instance = $this->getMock(LiveVersionUpdater::class, ['cloneRepository'], [$fs, __DIR__]);
        $instance->expects($this->once())->method('cloneRepository');

        $instance->updateRepository(true);
    }

    public function testCloneRepositoryDryRun()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveVersionUpdater($fs, '__DIR__');

        $this->assertEquals(-1, $instance->cloneRepository(true));
        $this->assertFileNotExists(storage_path('app/__DIR__'));
    }

    public function testCloneRepositoryShouldClone()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveVersionUpdater($fs, 'testclone');

        $this->assertEquals(0, $instance->cloneRepository());
        $this->assertFileExists(storage_path('app/testclone'));
        $this->assertTrue($instance->repositoryExists(), 'Failed asserting that the repository exists.');
    }

    public function testCloneRepositoryShouldMakeLive()
    {
        $fs = app(Filesystem::class);

        $instance = $this->getMock(LiveVersionUpdater::class, ['makeLiveVersion'], [$fs, 'makelive']);
        $instance->expects($this->once())->method('makeLiveVersion');

        /* @var $instance LiveVersionUpdater */
        if ($instance->repositoryExists()) {
            $instance->deleteRepository();
        }

        $instance->cloneRepository();
    }

    /**
     * @depends testCloneRepositoryShouldClone
     */
    public function testRebaseRepositoryDefaultWithExistingDir()
    {
        $fs = app(Filesystem::class);
        $instance = new LiveVersionUpdater($fs, storage_path('app/testclone'));

        $this->assertEquals(0, $instance->rebaseRepository());
    }

    /**
     * @depends testRebaseRepositoryDefaultWithExistingDir
     */
    public function testRemovesRepository()
    {
        $fs = app(Filesystem::class);

        $instance = new LiveVersionUpdater($fs, storage_path('app/testclone'));

        if (!$instance->repositoryExists())
        {
            $instance->updateRepository();
        }

        $this->assertTrue($instance->deleteRepository());
        $this->assertFileNotExists(storage_path('app/testclone'));
    }

    public function testGetRepositoryStatusWithExistingRepository()
    {
        $fs = app(Filesystem::class);

        $instance = new LiveVersionUpdater($fs, storage_path('app/testclone'));
        $instance->updateRepository();

        $information = $instance->getRepositoryStatus();

        $this->assertTrue(is_array($information));
        $this->assertTrue(count($information) == 2);

        $this->assertArrayHasKey('hash', $information);
        $this->assertArrayHasKey('last_modified', $information);

        $this->assertTrue(strlen($information['hash']) == 40);
        $this->assertInstanceOf(Carbon\Carbon::class, $information['last_modified']);

        // make sure to leave in a clean state!
        $instance->deleteRepository();
    }

    public function testGetRepositoryStatusWithNonExistingRepository()
    {
        $fs = app(Filesystem::class);

        $instance = new LiveVersionUpdater($fs, storage_path('app/testclone'));
        $instance->deleteRepository();

        $information = $instance->getRepositoryStatus();

        $this->assertTrue(is_array($information));
        $this->assertTrue(count($information) == 2);

        $this->assertArrayHasKey('hash', $information);
        $this->assertArrayHasKey('last_modified', $information);

        $this->assertEquals('<unknown>', $information['hash']);
        $this->assertNull($information['last_modified']);
    }

    public function testMakeLiveVersionDefault()
    {
        $fs = app(Filesystem::class);

        $instance = new LiveVersionUpdater($fs, storage_path('app/testclone'));
        if (!$instance->repositoryExists())
        {
            $instance->updateRepository();
        }

        $this->assertEquals(0, $instance->makeLiveVersion());
        $this->assertFileExists(storage_path('app/testclone/out/live.html'));

        $instance->deleteRepository();
    }

    public function testCleanMakeLiveVersion()
    {
        $fs = app(Filesystem::class);

        $instance = new LiveVersionUpdater($fs, storage_path('app/testclone'));
        if (!$instance->repositoryExists())
        {
            $instance->updateRepository();
        }

        $this->assertEquals(0, $instance->cleanLiveVersion());
        $this->assertFileNotExists(storage_path('app/testclone/out/live.html'));
    }

    public function testMakeLiveVersionNonExistentRepository()
    {
        $fs = app(Filesystem::class);

        $instance = new LiveVersionUpdater($fs, '__DIR__');
        $this->assertEquals(-2, $instance->makeLiveVersion());
        $this->assertFileNotExists(storage_path('app/__DIR__/out/live.html'));
    }

    public function testMakeLiveVersionDryRunShouldNotUpdate()
    {
        $fs = app(Filesystem::class);

        $instance = new LiveVersionUpdater($fs, storage_path('app/testclone'));
        if (!$instance->repositoryExists())
        {
            $instance->updateRepository();
        }

        $instance->makeLiveVersion();

        $lastModified = filemtime(storage_path('app/testclone/out/live.html'));

        $this->assertEquals(-1, $instance->makeLiveVersion(true));
        $this->assertEquals($lastModified, filemtime(storage_path('app/testclone/out/live.html')));
    }
}
