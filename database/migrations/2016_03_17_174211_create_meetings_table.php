<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            // oparl.id is not in the db layer
            // type is not in the db layer

            $table->string('name')->nullable();
            $table->string('meeting_state')->nullable();

            $table->boolean('cancelled')->nullable();

            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();

            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('oparl_locations');

            $table->unsignedInteger('invitation_id')->nullable();
            $table->foreign('invitation_id')->references('id')->on('oparl_files');

            $table->unsignedInteger('results_protocol_id')->nullable();
            $table->foreign('results_protocol_id')->references('id')->on('oparl_files');

            $table->unsignedInteger('verbatim_protocol_id')->nullable();
            $table->foreign('verbatim_protocol_id')->references('id')->on('oparl_files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_meetings');
    }
}
