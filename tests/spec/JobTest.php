<?php

use App\Jobs\SpecificationJob as OParlJob;

class JobTest extends TestCase
{
    public function testCreateSetsTreeish()
    {
        $sut = new OParlJob('other_value');
        $this->assertNotEmpty($sut->getTreeish());
        $this->assertEquals('other_value', $sut->getTreeish());
    }

    public function testGetTreeishDefaultValue()
    {
        $sut = new OParlJob();
        $this->assertTrue(method_exists($sut, 'getTreeish'));
        $this->assertEquals('', $sut->getTreeish());
    }

    public function testGetBuildMode()
    {
        $sut = new OParlJob();
        $this->assertTrue(method_exists($sut, 'getBuildMode'));
        $this->assertNotEmpty($sut->getBuildMode());
        $this->assertEquals(config('oparl.specificationBuildMode'), $sut->getBuildMode());

        config(['oparl.specificationBuildMode' => 'docker']);
        $sut = new OParlJob();
        $this->assertEquals('docker', $sut->getBuildMode());

        config(['oparl.specificationBuildMode' => 'unknown_build_mode']);
        $this->expectException(InvalidArgumentException::class);
        new OParlJob();
    }

    public function testPrepareCommand()
    {
        $sut = new OParlJob();

        $cmd = 'make live';
        $this->assertEquals($cmd, $sut->prepareCommand($cmd));

        $cmd = 'make DEBUG=%s live';
        $this->assertEquals('make DEBUG=false live', $sut->prepareCommand($cmd, 'false'));

        $cmd = '%s %d %1.1f';
        $this->assertEquals('make 1 1.1', $sut->prepareCommand($cmd, 'make', 1, 1.1));

        config(['oparl.specificationBuildMode' => 'docker']);

        $sut = new OParlJob();

        $cmd = 'make live';
        $this->assertEquals('docker run --rm -v $(pwd):$(pwd) -w $(pwd) oparl/specbuilder:latest make live', $sut->prepareCommand($cmd));
    }

    public function testRunSynchronousCommand()
    {
        $sut = new OParlJob();
        $cmd = 'echo "Hello World"';
        $this->assertTrue($sut->runSynchronousCommand('.', $cmd));
    }

    public function testCheckoutHubSyncToTreeish()
    {
        $repo = $this->repositoryProvider();

        $sut = new OParlJob();
        $result = $sut->checkoutHubSyncToTreeish($repo);
        $this->assertFalse($result); // repo is on master, should not return true
        $this->assertEquals('master', $repo->getCurrentTreeish());

        $sut = new OParlJob('~1.0');
        $result = $sut->checkoutHubSyncToTreeish($repo);
        $this->assertTrue($result);
        $this->assertEquals('a322ae0', $repo->getCurrentHead());

        $repo->remove();
    }

    public function testRunRepositoryCommand()
    {
        /** @var \App\Jobs\SpecificationJob|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(\App\Jobs\SpecificationJob::class)
            ->setMethods(['runSynchronousCommand'])
            ->getMock();

        $sut->expects($this->exactly(2))->method('runSynchronousCommand');

        $repo = $this->repositoryProvider();
        $sut->runRepositoryCommand($repo, 'ls');
        $sut->runRepositoryCommand($repo, 'ls', '-l', '-t', '*');
    }

    public function testRunCleanRepositoryCommand()
    {
        $fs = $this->app->make(\Illuminate\Contracts\Filesystem\Filesystem::class);

        /** @var \App\Services\HubSync\Repository|PHPUnit_Framework_MockObject_MockObject $sut */
        $repo = $this->getMockBuilder(\App\Services\HubSync\Repository::class)
            ->setMethods(['clean'])
            ->setConstructorArgs([$fs, 'test', 'tests/assets/test.git'])
            ->getMock();

        /** @var \App\Jobs\SpecificationJob|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(\App\Jobs\SpecificationJob::class)
            ->setMethods(['runRepositoryCommand'])
            ->getMock();

        $repo->expects($this->exactly(2))->method('clean');
        $sut->expects($this->once())->method('runRepositoryCommand')
            ->with($repo, 'ls');

        $sut->runCleanRepositoryCommand($repo, 'ls');

        $sut = $this->getMockBuilder(\App\Jobs\SpecificationJob::class)
            ->setMethods(['runRepositoryCommand'])
            ->getMock();

        $sut->expects($this->once())->method('runRepositoryCommand')
            ->with($repo, 'my', 'little', 'pony');

        $sut->runCleanRepositoryCommand($repo, 'my', 'little', 'pony');

        // Every method call is an assertion, PHPUnit counts mock method constraints only once
        $this->addToAssertionCount(2);
    }

    /**
     * @return \App\Services\HubSync\Repository
     */
    public function repositoryProvider()
    {
        $fs = $this->app->make(\Illuminate\Contracts\Filesystem\Filesystem::class);

        /** @var \App\Services\HubSync\Repository $repo */
        $repo = new \App\Services\HubSync\Repository($fs, 'test', 'tests/assets/test.git');
        $repo->update();

        return $repo;
    }
}
