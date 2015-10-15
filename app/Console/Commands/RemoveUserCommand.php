<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use Symfony\Component\Console\Input\InputOption;

class RemoveUserCommand extends Command
{
    /**
   * The name of the console command.
   *
   * @var string
   */
  protected $name = 'user:remove';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Remove a user by email address (deletes everything from that user).';

    protected function getOptions()
    {
        return [
      ['email', null, InputOption::VALUE_REQUIRED, 'E-Mail of the user to be removed']
    ];
    }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
      $email = $this->option('email');

      User::whereEmail($email)->delete();

      $this->info("User with email {$email} was deleted successfully.");
  }
}
