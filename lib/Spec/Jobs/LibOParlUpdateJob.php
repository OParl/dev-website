<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 27.04.17
 * Time: 10:30.
 */

namespace Spec\Jobs;

use App\Jobs\Job;
use EFrane\HubSync\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use OParl\Spec\Jobs\InteractsWithRepositoryTrait;

class LibOParlUpdateJob extends Job implements ShouldQueue
{
    use InteractsWithRepositoryTrait;
    use Queueable;
    use Notifiable;

    protected $prefix = '';

    public function __construct($treeish = '')
    {
        $this->prefix = config('oparl.liboparl.prefix');
    }

    public function handle(Filesystem $fs)
    {
        $repo = new Repository($fs, 'oparl_liboparl', 'https://');
    }
}
