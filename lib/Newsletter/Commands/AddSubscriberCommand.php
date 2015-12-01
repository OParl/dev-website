<?php namespace EFrane\Newsletter\Commands;

use EFrane\Newsletter\Model\Subscriber;
use Illuminate\Console\Command;

class AddSubscriberCommand extends Command
{
    protected $signature = 'newsletter:subscriber
        {name : Subscriber name}
        {email : Subscriber email address}
        {company? : Subscriber company (optional)}
        {--subscription : If not given, the subscriber will just be added to the system without any subscriptions}
    ';
    protected $description = 'Create a new subscriber record for the Newsletter module.';

    public function handle()
    {
        /* @var $validator \Illuminate\Contracts\Validation\Validator */
        $validator = \Validator::make($this->argument(), [
            'name' => 'string|min:3',
            'email' => 'email',
            'company' => 'sometimes|string|min:3'
        ]);

        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->all() as $message) {
                $this->error($message);
            };

            return;
        }

        $subscriber = Subscriber::firstOrNew([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'company' => $this->argument('company')
        ]);
    }
}