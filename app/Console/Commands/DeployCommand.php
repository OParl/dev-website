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
class DeployCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'deploy';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run commands necessary to put the application in a usable state.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
    if ($this->willRun())
    {
      $this->call('clear-compiled');
      $this->call('optimize');
    } else
    {
      $this->info('Use --force to run deploy commands in local mode.');
    }
	}

  /**
   * Check if the deployment will run (only if environment is *not* local)
   *
   * @return bool
   **/
  protected function willRun()
  {
    return !$this->getLaravel()->environment('local') || $this->option('force');
  }

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
      ['migrate', 'f', 'Run migrations during deployment.'],
    ];
	}
}
