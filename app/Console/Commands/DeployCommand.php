<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Deploy Command
 *
 * Runs commands and scripts necessary to put the application in a usable state.
 *
 * In the future, more actions like restarting queues may have to be taken care of.
 *
 * @package App\Console\Commands
 **/
class DeployCommand extends Command
{
    protected $signature = 'deploy {--update-dependencies}';
    protected $description = 'Run commands necessary to put the application in a usable state.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ($this->option('update-dependencies')) {
            exec('npm install', $output);
            $this->line(implode("\n", $output));

            exec('bower update --allow-root', $output);
            $this->line(implode("\n", $output));

            exec('bower install --allow-root', $output);
            $this->line(implode("\n", $output));

            exec('gulp --production', $output);
            $this->line(implode("\n", $output));
        }

        $this->call('clear-compiled');
        $this->call('optimize');
    }
}
