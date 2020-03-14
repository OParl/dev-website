<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

use App\Services\EndpointLocator;

class EndpointLocatorTest extends TestCase
{

    public function testLookup()
    {
        $item = 67333;

        /** @var EndpointLocator $sut */
        $sut = app(EndpointLocator::class);

        $sut->lookup($item);
    }
}
