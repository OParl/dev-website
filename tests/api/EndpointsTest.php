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
        $this->assertResponseOk();
    }

    public function limitProvider(): array
    {
        return [
            'no limit => default, default page' => [
                [], 25
            ],
            'no limit => default, first page' => [
                ['page' => 1], 25
            ],
            'limit 1, default page' => [
                ['limit' => 1], 1
            ],
            'limit 1, first page' => [
                ['limit' => 1, 'page' => 1], 1
            ],
            'limit 1, second page' => [
                ['limit' => 1, 'page' => 1], 1
            ],
        ];
    }

    /**
     * @dataProvider limitProvider
     */
    public function testLimit($params, $count)
    {
        $this->route('get', 'api.endpoints.index', $params);
        $this->assertResponseOk();

        $data = json_decode($this->response->getContent(), true);

        $this->assertCount($count, $data['data']);
        $this->assertIsArray($data['data']);
    }
}
