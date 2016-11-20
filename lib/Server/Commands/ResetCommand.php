<?php

namespace OParl\Server\Commands;

use Illuminate\Console\Command;
use OParl\Server\Model\Body;
use OParl\Server\Model\Consultation;
use OParl\Server\Model\File;
use OParl\Server\Model\Keyword;
use OParl\Server\Model\LegislativeTerm;
use OParl\Server\Model\Location;
use OParl\Server\Model\Meeting;
use OParl\Server\Model\Membership;
use OParl\Server\Model\Organization;
use OParl\Server\Model\Paper;
use OParl\Server\Model\Person;
use OParl\Server\Model\System;

/**
 * Clear the server data
 *
 * @package OParl\Server\Commands
 */
class ResetCommand extends Command
{
    protected $name = 'server:reset';
    protected $description = "Reset the server's database";

    public function handle()
    {
        $this->info('Removing all existing server data...');

        System::truncate();
        Body::truncate();
        LegislativeTerm::truncate();
        Person::truncate();
        Organization::truncate();
        Membership::truncate();
        Meeting::truncate();
        Consultation::truncate();
        Paper::truncate();
        Location::truncate();
        File::truncate();
        Keyword::truncate();
    }
}