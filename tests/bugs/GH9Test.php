<?php

class GH9Test extends TestCase {
    public function testFetchOrganizationForBody() {
        $this->json('get', '/api/v1/organization?where=body%3A2')
            ->seeJsonContains(['body' => route('api.v1.body.show', 2)]);
    }
}
