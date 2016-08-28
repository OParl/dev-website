<?php

class SystemTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.v1.system.index');
        $this->assertResponseStatus(200);
        $this->seeJson(['name' => 'OParl Demoserver']);
    }

    public function testShow()
    {
        $this->route('get', 'api.v1.system.show', [1]);
        $this->assertResponseStatus(200);
        $this->seeJson(['name' => 'OParl Demoserver']);
    }
}
