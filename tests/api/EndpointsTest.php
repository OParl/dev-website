<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 19.05.17
 * Time: 13:21.
 */
class EndpointsTest extends TestCase
{
    public function testEndpointIndex()
    {
        $this->route('get', 'api.endpoints.index');
        $this->assertResponseStatus(200);
    }
}
