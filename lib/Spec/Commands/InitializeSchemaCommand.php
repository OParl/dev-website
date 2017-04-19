<?php

namespace OParl\Spec\Commands;

class InitializeSchemaCommand extends Command
{
    protected $name = 'oparl:init:schema';
    protected $description = 'Fetches currently required schema files.';

    public function handle()
    {
        $this->call('oparl:update:schema', ['constraint' => 'master']);
        $this->call('oparl:update:schema', ['constraint' => '~1.0']);
    }
}
