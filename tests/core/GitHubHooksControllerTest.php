<?php

class GitHubHooksControllerTest extends \TestCase
{
    use \Illuminate\Foundation\Testing\WithoutMiddleware;

    public function testIndex()
    {
        $this->get(route('hooks.gh.index'));
        $this->assertResponseStatus(400);

        $this->post(route('hooks.gh.index.post'));
        $this->assertResponseStatus(400);
    }

    public function testUnknownRepositoryBadRequest()
    {
        $this->post(route('hooks.gh.push', ['supercalifragilisticexpialigocius']));
        $this->assertResponseStatus(400);
    }

    public function testPing()
    {
        $this->post(route('hooks.gh.push', ['spec']), [], ['x-github-event' => 'ping']);

        $this->assertResponseOk();
        $this->seeJsonStructure(['result']);
    }

    public function testPong()
    {
        $this->post(route('hooks.gh.push', ['spec']), [], ['x-github-event' => 'unsupported-event']);

        $this->assertResponseOk();
        $this->seeJsonStructure(['result']);
    }

    public function testPushDispatchesJob()
    {
        $this->expectsJobs(\App\Jobs\GitHubPushJob::class);
        $this->post(
            route('hooks.gh.push', ['spec']),
            ['payload'        => json_encode(['action' => 'closed'])],
            ['x-github-event' => 'push']
        );

        $this->assertResponseOk();
    }

    public function testPullRequestWithMergeDispatchesJob()
    {
        $this->expectsJobs(\App\Jobs\GitHubPushJob::class);
        $this->post(route('hooks.gh.push', ['spec']), [
            'payload' => json_encode(['action' => 'closed', 'merged' => true]),
        ], ['x-github-event' => 'pull_request']);

        $this->assertResponseOk();
    }

    public function testPullRequestDefaultsToNoDispatch()
    {
        $this->doesntExpectJobs(\App\Jobs\GitHubPushJob::class);
        $this->post(route('hooks.gh.push', ['spec']), ['x-github-event' => 'pull_request']);

        $this->assertResponseOk();
    }

    /**
     * @dataProvider pushWithBadRequestDataProvider
     */
    public function testPushWithGetRequestIsBadRequest($repository)
    {
        $this->doesntExpectJobs(\App\Jobs\GitHubPushJob::class);
        $this->get(route('hooks.gh.push.get', [$repository]));

        $this->assertResponseStatus(400);
    }

    public function pushWithBadRequestDataProvider()
    {
        return collect([
            'foo',
            'bar',
            'spec',
            'liboparl',
        ])->map(function ($repo) {
            return [$repo];
        })->toArray();
    }
}
