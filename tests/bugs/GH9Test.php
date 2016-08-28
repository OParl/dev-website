<?php

class GH9Test extends TestCase {
    public function testFetchOrganizationForBody() {
        $orgas = \OParl\Server\Model\Organization::where('body_id', '=', 1)->get();

        $this->assertTrue($orgas->count() > 0);
    }
}
