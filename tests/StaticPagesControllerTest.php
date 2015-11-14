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
            ->see('OParl wird unterstützt von');
    }

//    public function testStatus()
//    {
//        $this->visit('/status')
//            ->see('Hier sammeln wir Informationen zum Stand der Standard-Entwicklung (Spezifikation).')
//            ->see('Ein Projekt von:');
//    }
}
