<?php

class OrganizationTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.oparl.v1.organization.index');
        $this->assertResponseStatus(200);

        $responseOrganizationCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $organizationCount = \OParl\Server\Model\Organization::count();

        $this->assertEquals($organizationCount, $responseOrganizationCount);
    }

    public function testShow()
    {
        $this->route('get', 'api.oparl.v1.organization.show', [1]);
        $this->assertResponseStatus(200);

        $entity = \OParl\Server\Model\Organization::find(1);

        $this->seeJsonContains(['name' => $entity->name]);
    }
}
