<?php

class PaperTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.oparl.v1.paper.index');
        $this->assertResponseStatus(200);

        $responsePaperCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $paperCount = \OParl\Server\Model\Paper::count();

        $this->assertEquals($paperCount, $responsePaperCount);
    }

    public function testShowFailsWithNonExisting()
    {
        $this->route('get', 'api.oparl.v1.paper.show', [0]);
        $this->assertResponseStatus(404);
        $this->seeJson([
            'error' => [
                'message' => 'The requested item in `Paper` does not exist.',
                'status'  => 404,
            ],
        ]);
    }
}
