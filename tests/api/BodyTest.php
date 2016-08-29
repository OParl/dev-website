<?php

class BodyTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.v1.body.index');
        $this->assertResponseStatus(200);

        $responseBodyCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $bodyCount = \OParl\Server\Model\Body::count();

        $this->assertEquals($bodyCount, $responseBodyCount);
    }

    public function testShow()
    {
        $this->route('get', 'api.v1.body.show', [1]);
        $this->assertResponseStatus(200);

        $entity = \OParl\Server\Model\Body::find(1);

        $this->seeJsonContains(['name' => $entity->name]);
    }
}
