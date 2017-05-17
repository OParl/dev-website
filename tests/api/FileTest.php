<?php

class FileTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.oparl.v1.file.index');
        $this->assertResponseStatus(200);

        $responseFileCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $fileCount = \OParl\Server\Model\File::count();

        $this->assertEquals($fileCount, $responseFileCount);
    }

    public function testShow()
    {
        $this->route('get', 'api.oparl.v1.file.show', [1]);
        $this->assertResponseStatus(200);

        $entity = \OParl\Server\Model\File::find(1);

        $this->seeJsonContains(['sha1Checksum' => $entity->sha1_checksum]);
    }

    public function testShowNotExistsError()
    {
        $this->route('get', 'api.oparl.v1.file.show', [0]);
        $this->assertResponseStatus(404);
        $this->seeJson([
            'error' => [
                'message' => 'The requested item in `File` does not exist.',
                'status'  => 404,
            ],
        ]);
    }
}
