<?php namespace EFrane\Newsletter;

use Illuminate\Support\ServiceProvider;

class NewsletterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
           __DIR__.'/config/newsletter.php' => config_path('newsletter.php')
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/newsletter.php', 'newsletter');

        $this->commands([
            Commands\AddSubscriberCommand::class,
            Commands\AddSubscriptionCommand::class
        ]);
    }
}