<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Clear the server data.
 */
class ServerResetCommand extends Command
{
    protected $name = 'server:reset';
    protected $description = "Reset the demoserver's database";

    public function handle()
    {
        $this->info('Reset demoserver database...');

        $demodataDB = config('database.connections.sqlite_demo.database');

        if (file_exists($demodataDB)) {
            unlink($demodataDB);
        }

        touch($demodataDB);

        $this->call('migrate', ['--database' => 'sqlite_demo']);
    }
}
