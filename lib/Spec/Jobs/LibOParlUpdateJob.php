<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 27.04.17
 * Time: 10:30
 */

namespace Spec\Jobs;

use App\Jobs\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;

class LibOParlUpdateJob extends Job implements ShouldQueue
{
    use Queueable;
    use Notifiable;

    public function handle()
    {

    }
}