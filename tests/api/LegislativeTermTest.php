<?php

class LegislativeTermTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.oparl.v1.legislativeterm.index');
        $this->assertResponseStatus(200);

        $responseLegislativeTermCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $legislativetermCount = \OParl\Server\Model\LegislativeTerm::count();

        $this->assertEquals($legislativetermCount, $responseLegislativeTermCount);
    }

    public function testShow()
    {
        $this->route('get', 'api.oparl.v1.legislativeterm.show', [1]);
        $this->assertResponseStatus(200);

        $entity = \OParl\Server\Model\LegislativeTerm::find(1);

        $this->seeJsonContains(['name' => $entity->name]);
    }
}
