<?php

class GitHubHooksControllerTest extends \TestCase
{
    public function testIndex()
    {
        $this->get(route('hooks.gh.index'));
        $this->assertResponseStatus(400);
    }

    public function testPing()
    {
        $this->get(route('hooks.gh.push', ['oparl.spec']), ['x-github-event', 'ping']);

        $this->assertResponseOk();
        $this->seeJsonStructure(['result']);
    }

    public function testPong()
    {
        $this->get(route('hooks.gh.push', ['oparl.spec']), ['x-github-event', 'unsupported-event']);

        $this->assertResponseOk();
        $this->seeJsonStructure(['result']);
    }

    public function testPushDispatchesJob()
    {
        $this->markTestIncomplete();
    }

    public function testPullRequestWithMergeDispatchesJob()
    {
        $this->markTestIncomplete();
    }

    public function testPullRequestDefaultsToNoDispatch()
    {
        $this->markTestIncomplete();
    }
}
