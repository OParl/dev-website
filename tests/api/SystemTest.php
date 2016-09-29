<?php

class SystemTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.v1.system.index');
        $this->assertResponseStatus(200);
        $this->seeJson(['name' => 'OParl Demoserver']);
    }

    public function testHasBodies()
    {
        $this->route('get', 'api.v1.system.index');

        $this->assertTrue(is_string($this->decodeResponseJson()['body']));

    }
/*
    public function testStructureIsValid()
    {
        // NOTE: This test is separate from testShow purely for code cleanliness

        $this->route('get', 'api.v1.system.index');
        $this->seeJsonStructure(
            [
                "id"           => '*',
                "type"         => '*',
                "oparlVersion" => '*',
                "name"         => 'OParl Demoserver',
                "vendor"       => '*',
                "product"      => '*',
                "contactName"  => '*',
                "contactEmail" => '*',
                "website"      => '*',
                "created"      => '*',
                "modified"     => '*',
                "deleted"      => false,
            ]
        );
    }*/
}
