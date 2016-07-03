<?php

class GH9Test extends TestCase {
    public function testFetchOrganizationForBody() {
        // FIXME: write code for test data creation using factorys
        $this->markTestSkipped('Currently not working.');
        $this->call('get', '/api/v1/organization?where=body%3A2');
        $this->assertResponseStatus(200);
    }
}
