<?php

class GH9Test extends TestCase
{
    public function testOrganizationHasBody()
    {
        $orgas = \OParl\Server\Model\Organization::where('body_id', '=', 1)->get();

        $this->assertTrue($orgas->count() > 0);
    }

    public function testFetchOrganizationWhereBodyCondition()
    {
        $this->route('get', 'api.v1.organization.index', ['where' => 'body:1']);

        $this->assertResponseStatus(200);
        $this->seeJson(["body" => "http://dev.dev-website.dev/api/v1/body/1"]);
    }
}
