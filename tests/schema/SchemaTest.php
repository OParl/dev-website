<?php

class SchemaTest extends TestCase
{
    /**
     * @dataProvider schemaNames
     */
    public function testSchema($name)
    {
        $this->route('get', 'schema.get', ['1.0', $name]);
        $this->assertResponseOk();
        $this->assertJson($this->response->getContent());

        $this->route('get', 'schema.get', ['1.1', $name]);
        $this->assertResponseOk();
        $this->assertJson($this->response->getContent());

        $this->route('get', 'schema.get', ['master', $name]);
        $this->assertResponseOk();
        $this->assertJson($this->response->getContent());
    }

    /**
     * Since we did not have standalone schema files for all entities on 1.0
     * this test only applies to OParl >= 1.1
     *
     * @dataProvider schemaNames
     */
    public function testType($name)
    {
        $this->route('get', 'schema.get', ['1.0', $name]);

        $typeName = strtolower($name);
        $regex = preg_quote("https://schema.oparl.org/1.0/{$typeName}");

        //$this->assertContains($regex, $this->response->getContent());
        $this->markTestSkipped('This test is currently not usable as our regexes are broken.');
    }

    public function schemaNames()
    {
        return [
            ['System'],
            ['Body'],
            ['LegislativeTerm'],
            ['Person'],
            ['Organization'],
            ['Membership'],
            ['Meeting'],
            ['AgendaItem'],
            ['Consultation'],
            ['Paper'],
            ['File'],
            ['Location'],
        ];
    }
}
