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
}