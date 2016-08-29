<?php

class LocationTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.v1.location.index');
        $this->assertResponseStatus(200);

        $responseLocationCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $locationCount = \OParl\Server\Model\Location::count();

        $this->assertEquals($locationCount, $responseLocationCount);
    }

    public function testShow()
    {
        $this->route('get', 'api.v1.location.show', [1]);
        $this->assertResponseStatus(200);

        $entity = \OParl\Server\Model\Location::find(1);

        $this->seeJsonContains(['description' => $entity->description]);
    }
}
