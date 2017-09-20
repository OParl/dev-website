<?php

class GH54Test extends TestCase
{
    public function testLocationPostalCodeIsString()
    {
        /** @var \OParl\Server\Model\Location $location */
        $location = \OParl\Server\Model\Location::first();
        $this->assertInstanceOf(\OParl\Server\Model\Location::class, $location);
        $this->assertTrue(is_string($location->postal_code));
    }

    public function testApiLocationPostalCodeIsString()
    {
        $this->route('get', 'api.oparl.v1.location.show', ['id' => 1]);

        $this->assertResponseStatus(200);

        $postalCode = $this->decodeResponseJson()['postalCode'];
        $this->assertTrue(is_string($postalCode));
    }
}
