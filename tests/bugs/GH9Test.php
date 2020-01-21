<?php

class GH9Test extends TestCase
{
    public function testOrganizationHasBody()
    {
        $this->markTestSkipped('Skipped because the demoserver is currently disabled');

        $orgas = \OParl\Server\Model\Organization::where('body_id', '=', 1)->get();

        $this->assertTrue($orgas->count() > 0);
    }

    public function testFetchOrganizationWhereBodyCondition()
    {
        $this->markTestSkipped('Skipped because the demoserver is currently disabled');

        $this->route('get', 'api.oparl.v1.organization.index', ['where' => encode_where(['body' => '1'])]);

        $this->assertResponseStatus(200);

        $host = env('APP_URL');
        $this->seeJson(['body' => "http://dev.{$host}/api/oparl/v1/body/1"]);
    }
}
