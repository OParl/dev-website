<?php

class GH9Test extends TestCase {
    public function testFetchOrganizationForBody() {
        $this->call('get', '/api/v1/organization?where=body%3A2');
        $this->assertResponseStatus(200);
    }
}
