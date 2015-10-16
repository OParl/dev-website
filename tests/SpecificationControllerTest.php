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
        $this->visit('/spezifikation');

        $this->see('Spezifikation - OParl.org'); // title

        $this->see('OParl.'); // header
        $this->matchesRegularExpression('<li role="presentation" class="active">\w*<a href="http://localhost/spezifikation">\w*Spezifikation\w*</a>\w*</li>'); // menu

        // NOTE: not testing for the loaded livecopy contents since that requires pandoc
        //       in the testing environment
    }

    public function testRaw()
    {
        $request = $this->call('GET', '/spezifikation.md');

        $this->assertEquals(200, $request->status());
    }
}
