<?php

class ConsultationTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.v1.consultation.index');
        $this->assertResponseStatus(200);

        $responseConsultationCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $consultationCount = \OParl\Server\Model\Consultation::count();

        $this->assertEquals($consultationCount, $responseConsultationCount);
    }

    public function testShowNotExistsError()
    {
        $this->route('get', 'api.v1.consultation.show', [0]);
        $this->assertResponseStatus(404);
        $this->seeJson([
            'error' => [
                'message' => 'The requested item in `Consultation` does not exist.',
                'status'  => 404,
            ],
        ]);
    }
}
