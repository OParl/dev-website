<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_meetings_organizations', function (Blueprint $table) {
            $table->unsignedInteger('meeting_id');
            $table->foreign('meeting_id')->references('id')->on('oparl_meetings');

            $table->unsignedInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('oparl_organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_meetings_organizations');
    }
}
