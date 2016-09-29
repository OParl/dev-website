<?php

class SchemaTest extends TestCase
{
    /**
     * @dataProvider schemaNames
     */
    public function testSchema($name)
    {
        $this->route('get', 'schema.get', [$name]);
        $this->assertResponseOk();

        $typeIdentifier = "https://schema.oparl.org/1.0/{$name}";
        // TODO: match type on type regex
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