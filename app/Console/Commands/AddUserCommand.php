<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Model\User;

class AddUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'user:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a user.';

    protected function getOptions()
    {
        return [
            ['name', null, InputOption::VALUE_REQUIRED, 'The user\'s name'],
            ['email', null, InputOption::VALUE_REQUIRED, 'The user\'s email']
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $password = $this->generateRandomPassword(16);

        $user = User::create([
            'name' => $this->option('name'),
            'email' => $this->option('email'),
            'password' => bcrypt($password)
        ]);

        $this->info('User ' . $user->name . ' successfully created with ID ' . $user->id);
        $this->line('Temporary password: ' . $password);
    }

    // TODO: extract that to a separate class
    protected function generateRandomPassword($length = 8)
    {
        $chars = "abcdefghklmnopqrstuvxyzABCDEFGHKLMNOPQRSTUVXYZ0123456789-:;,/&%(#+*";

        $password = '';

        mt_srand();
        do {
            $char = mt_rand(0, strlen($chars) - 1);
            $password .= $chars[$char];
        } while (strlen($password) < $length);

        return $password;
    }
}
