<?php namespace OParl\Spec\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class SpecificationJob implements SelfHandling, ShouldQueue
{
    protected $user = 'OParl';
    protected $repo = 'spec';

    use Queueable;
    use InteractsWithQueue;
    use SerializesModels;
}
