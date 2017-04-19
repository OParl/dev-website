<?php

class SynchronousProcessTest extends TestCase
{
    public function testEmptyOutputWithoutCommand()
    {
        $processObject = $this->getObjectForTrait(\EFrane\HubSync\SynchronousProcess::class);
        $output = $processObject->synchronousProcess('');
        $this->assertEquals('', $output);
    }

    public function testHasOutputWithCommand()
    {
        $processObject = $this->getObjectForTrait(\EFrane\HubSync\SynchronousProcess::class);
        $output = $processObject->synchronousProcess('echo "Hello Test"');
        $this->assertNotEmpty($output);
        $this->assertEquals('Hello Test', $output);
    }
}
