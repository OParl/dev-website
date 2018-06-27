<?php

namespace OParl\Spec\Commands;

class InitializeSchemaCommand extends Command
{
    protected $name = 'oparl:init:schema';
    protected $description = 'Fetches currently required schema files.';

    public function handle()
    {
        collect(config('oparl.schema'))->unique()->each(function ($constraint) {
            $this->call('oparl:update:schema', ['treeish' => $constraint]);
        });
    }
}
