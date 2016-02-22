<?php

namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

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
            ['email', null, InputOption::VALUE_REQUIRED, 'The user\'s email'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $password = str_random(12);

        $user = User::create([
            'name'     => $this->option('name'),
            'email'    => $this->option('email'),
            'password' => bcrypt($password),
        ]);

        $this->info('User '.$user->name.' successfully created with ID '.$user->id);
        $this->line('Temporary password: '.$password);
    }
}
