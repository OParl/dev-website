<?php

use OParl\Spec\Jobs\Job as OParlJob;

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
        $this->assertNotNull($sut->getTreeish());
        $this->assertEquals('master', $sut->getTreeish());
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
        $this->assertEquals('make 1 1,1', $sut->prepareCommand($cmd, 'make', 1, 1.1));

        config(['oparl.specificationBuildMode' => 'docker']);

        $sut = new OParlJob();

        $cmd = 'make live';
        $this->assertEquals('docker run --rm -v $(pwd):$(pwd) -w $(pwd) oparl/specbuilder:latest make live', $sut->prepareCommand($cmd));
    }

    public function testNotifySlack()
    {
        config(['slack.enabled' => false]);
        $sut = new OParlJob();

        $this->assertFalse($sut->notifySlack('Message'));

        config(['slack.enabled' => true]);
        $this->assertTrue($sut->notifySlack('Message'));
    }

    public function testRunSynchronousJob()
    {
        $sut = new OParlJob();
        $cmd = 'echo "Hello World"';
        $this->assertTrue($sut->runSynchronousJob('.', $cmd));
    }
}