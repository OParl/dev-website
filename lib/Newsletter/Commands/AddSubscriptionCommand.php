<?php namespace EFrane\Newsletter\Commands;

use Illuminate\Console\Command;

class AddSubscriptionCommand extends Command
{
    protected $signature = 'newsletter:subscription {name : Newsletter identifier} {--description : An optional newsletter description}?';
    protected $description = 'Create a new newsletter subscription list.';

    public function handle()
    {
        $this->line('Creating subscription '.$this->argument('name'));
    }
}