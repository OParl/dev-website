<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use OParl\Spec\LiveVersionRepository;
use Symfony\Component\Finder\Finder;

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
    }

    public function testRaw()
    {
        $request = $this->call('GET', '/spezifikation.md');

        $this->assertEquals(200, $request->status());
        $this->assertContains('OParl-Spezifikation', $request->getContent());
    }

    public function testImageNoDirectoryIndex()
    {
        $request = $this->call('GET', '/spezifikation/images/');

        $this->assertEquals(404, $request->status());
    }

    public function testImageGet()
    {
        // first, get all images
        $images = Finder::create()->in(LiveVersionRepository::getImagesPath('', true))->name('*.png');

        foreach ($images as $image) {
            /* @var $image \SplFileInfo */
            $url = "/spezifikation/images/{$image->getBasename()}";
            $request = $this->call('GET', $url);

            $this->assertEquals(200, $request->status());
        }
    }
}
