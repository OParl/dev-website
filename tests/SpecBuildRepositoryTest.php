<?php

use OParl\Spec\BuildRepository;

class SpecBuildRepositoryTest extends TestCase
{
    public function testCreate()
    {
        // it should be sufficient to check if the build repository can be resolved
        // through the ioc container
        $instance = app()->make(BuildRepository::class);

        $this->assertInstanceOf(OParl\Spec\BuildRepository::class, $instance);
    }
}
