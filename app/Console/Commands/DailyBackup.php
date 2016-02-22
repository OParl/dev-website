<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;

class DailyBackup extends Command implements SelfHandling
{
    protected $signature = 'backup:daily {host}';
    protected $description = 'Backup command intended to be run on a daily basis.';

    public function handle()
    {
        // TODO: implement using https://github.com/backup-manager/backup-manager
    }
}
