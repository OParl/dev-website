<?php

class SchemaTest extends TestCase
{
    /**
     * @dataProvider schemaNames
     * @param string $name entity name
     */
    public function testSchemaServesAndIsJSON1_0($name)
    {
        $this->route('get', 'schema.get', ['1.0', $name]);
        $this->assertResponseOk();
        $this->assertJson($this->response->getContent());
    }

    /**
     * @dataProvider schemaNames
     * @param string $name entity name
     */
    public function testSchemaServesAndIsJSON1_1($name)
    {
        $this->route('get', 'schema.get', ['1.1', $name]);
        $this->assertResponseOk();
        $this->assertJson($this->response->getContent());
    }

    /**
     * @dataProvider schemaNames
     * @param string $name entity name
     */
    public function testSchemaServesAndIsJSONmaster($name)
    {
        $this->route('get', 'schema.get', ['master', $name]);
        $this->assertResponseOk();
        $this->assertJson($this->response->getContent());
    }

    /**
     * Since we did not have standalone schema files for all entities on 1.0
     * this test only applies to OParl >= 1.1
     *
     * @dataProvider schemaNames
     * @param string $name entity name
     */
    public function testTypeFor1_0($name)
    {
        $this->route('get', 'schema.get', ['1.0', $name]);

        $typeName = strtolower($name);
        $regex = preg_quote("https://schema.oparl.org/1.0/{$typeName}", '/');

        $this->assertContains($regex, $this->response->getContent());
    }

    /**
     * @dataProvider schemaNames
     * @param string $name entity name
     */
    public function testTitle_1_0($name) {
        $this->route('get', 'schema.get', ['1.0', $name]);
        $this->assertContains('"title": "' . $name .'"', $this->response->getContent());
    }

    /**
     * @dataProvider schemaNames
     * @param string $name entity name
     */
    public function testTitle_master($name) {
        $this->route('get', 'schema.get', ['master', $name]);
        $this->assertContains('"title": "' . $name .'"', $this->response->getContent());
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
