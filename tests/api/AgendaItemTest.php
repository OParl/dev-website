<?php

class AgendaItemTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.oparl.v1.agendaitem.index');
        $this->assertResponseStatus(200);

        $responseAgendaItemCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $agendaitemCount = \OParl\Server\Model\AgendaItem::count();

        $this->assertEquals($agendaitemCount, $responseAgendaItemCount);
    }

    public function testShow()
    {
        $this->route('get', 'api.oparl.v1.agendaitem.show', [1]);
        $this->assertResponseStatus(200);

        $entity = \OParl\Server\Model\AgendaItem::find(1);

        $this->seeJsonContains(['name' => $entity->name]);
    }
}
