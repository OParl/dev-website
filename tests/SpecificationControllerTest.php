<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class SpecificationControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @return void
     */
    public function testShow()
    {
        $this->visit('/spezifikation')->see('OParl-Spezifikation');
    }
}
