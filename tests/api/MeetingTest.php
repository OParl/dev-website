<?php

class MeetingTest extends TestCase
{
    public function testIndex()
    {
        $this->route('get', 'api.oparl.v1.meeting.index');
        $this->assertResponseStatus(200);

        $responseMeetingCount = $this->decodeResponseJson()['pagination']['totalElements'];
        $meetingCount = \OParl\Server\Model\Meeting::count();

        $this->assertEquals($meetingCount, $responseMeetingCount);
    }

    public function testShow()
    {
        $this->route('get', 'api.oparl.v1.meeting.show', [1]);
        $this->assertResponseStatus(200);

        $entity = \OParl\Server\Model\Meeting::find(1);

        $this->seeJsonContains(['name' => $entity->name]);
    }
}
