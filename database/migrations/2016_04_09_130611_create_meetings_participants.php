<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetingsParticipants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_meetings_participants', function (Blueprint $table) {
            $table->unsignedInteger('meeting_id');
            $table->foreign('meeting_id')->references('id')->on('oparl_meetings');

            $table->unsignedInteger('participant_id');
            $table->foreign('participant_id')->references('id')->on('oparl_people');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_meetings_participants');
    }
}
