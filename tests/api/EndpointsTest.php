<?php

use App\Http\Controllers\API\EndpointApiController;
use App\Model\Endpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 19.05.17
 * Time: 13:21.
 */
class EndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        if (Endpoint::all()->count() === 0) {
            factory(Endpoint::class, 50)->create();
        }
    }

    public function limitProvider(): array
    {
        return [
            'no limit => default, default page' => [
                [], EndpointApiController::DEFAULT_LIMIT
            ],
            'no limit => default, first page' => [
                ['page' => 1], EndpointApiController::DEFAULT_LIMIT
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
     * @param array $params
     * @param int $count
     */
    public function testLimit(array $params, int $count): void
    {
        $this->route('get', 'api.endpoints.index', $params);
        $this->assertResponseOk();

        $data = json_decode($this->response->getContent(), true);

        $this->assertCount($count, $data['data']);
        $this->assertIsArray($data['data']);
    }

    public function testLinks()
    {
        $initialRequest = $this->doGetJson($this->app['url']->route('api.endpoints.index'));

        $this->assertArrayHasKey('self', $initialRequest['meta']);
        $this->assertArrayHasKey('next', $initialRequest['meta']);

        $selfRequest = $this->doGetJson($initialRequest['meta']['self']);

        $this->assertArrayHasKey('self', $selfRequest['meta']);
        $this->assertArrayHasKey('next', $selfRequest['meta']);

        $nextRequest = $selfRequest;
        while (null !== $nextRequest['meta']['next']) {
            $nextRequest = $this->doGetJson($nextRequest['meta']['next']);

            $this->assertArrayHasKey('self', $nextRequest['meta']);
            $this->assertArrayHasKey('next', $nextRequest['meta']);
        }

        $errorRequest = $this->route(
            'get',
            'api.endpoints.index',
            ['page' => $nextRequest['meta']['page'] + 1, 'limit' => $nextRequest['meta']['perPage']]
        );

        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);

        $this->assertEquals('{"error":"Not found."}', $errorRequest->getContent());
    }

    protected function doGetJson($uri): array
    {
        $this->get($uri);

        $this->assertResponseOk();

        return json_decode($this->response->getContent(), true);
    }
}
