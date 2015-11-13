<?php

class StaticPagesControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testImprint()
    {
        $this->visit('/impressum')
            ->see('Anbieter im Sinne des § 5 Telemediengesetzes')
            ->see('OParl wird unterstützt durch:');
    }

    public function testStatus()
    {
        $this->visit('/status')
            ->see('Hier sammeln wir Informationen zum Stand der Standard-Entwicklung (Spezifikation).')
            ->see('OParl wird unterstützt durch:');
    }

    public function testAbout()
    {
        $this->visit('/ueber-oparl')
            ->see('Viele Kommunen, Landkreise und Regionen')
            ->see('OParl wird unterstützt durch:');
    }
}
