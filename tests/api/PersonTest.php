<?php

class PersonTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.v1.person.index');
        $this->assertResponseStatus(200);

        $responsePersonCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $personCount = \OParl\Server\Model\Person::count();

        $this->assertEquals($personCount, $responsePersonCount);
    }

    public function testShow()
    {
        $this->route('get', 'api.v1.person.show', [1]);
        $this->assertResponseStatus(200);

        $entity = \OParl\Server\Model\Person::find(1);

        $this->seeJsonContains(['name' => $entity->name]);
    }
}
