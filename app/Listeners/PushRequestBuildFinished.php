<?php

namespace App\Listeners;

use App\Events\RequestedBuildFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushRequestBuildFinished
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RequestedBuildFinished  $event
     * @return void
     */
    public function handle(RequestedBuildFinished $event)
    {
        //
    }
}
