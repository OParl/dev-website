<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Deploy Command
 *
 * Runs commands and scripts necessary to put the application in a usable state.
 *
 * This includes but is not limited to
 *
 * - artisan clear-compiled,
 * - artisan optimize,
 *
 * and gulp.
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
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

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

      if ($this->option('init'))
      {
        exec('npm install');
        exec('bower update --allow-root');

        $this->call('maintenance:livecopy', ['--force' => true]);
        $this->call('maintenance:versions');
      }

      exec('gulp');

      //$this->call('migrate', ['--force' => true]);
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
   * This command accepts two options,
   *
   * 1. force,f - Forces production mode (deployment commands will be run).
   * 2. init - Initializes the tools necessary for deployment.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
      ['force', 'f', InputOption::VALUE_NONE, 'Force production mode.'],
      ['init', null, InputOption::VALUE_NONE, 'Initialize deployment infrastructure.'],
    ];
	}
}
