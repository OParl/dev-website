<?php

class GH54Test extends TestCase
{
    public function testLocationPostalCodeIsString()
    {
        $this->markTestSkipped('Skipped because the demoserver is currently disabled');

        /** @var \OParl\Server\Model\OParl10Location $location */
        $location = \OParl\Server\Model\OParl10Location::first();
        $this->assertInstanceOf(\OParl\Server\Model\OParl10Location::class, $location);
        $this->assertTrue(is_string($location->postal_code));
    }

    public function testApiLocationPostalCodeIsString()
    {
        $this->markTestSkipped('Skipped because the demoserver is currently disabled');

        $this->route('get', 'api.oparl.v1.location.show', ['id' => 1]);

        $this->assertResponseStatus(200);

        $postalCode = $this->decodeResponseJson()['postalCode'];
        $this->assertTrue(is_string($postalCode));
    }
}
