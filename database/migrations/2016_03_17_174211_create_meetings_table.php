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

            $table->dateTime('start');
            $table->dateTime('end');

            $table->string('room')->nullable();
            $table->string('street_address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('locality')->nullable();

            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('oparl_locations');

            // TODO: organization is 1:n
            // TODO: participant is 1:n

            $table->unsignedInteger('invitation_id')->nullable();
            $table->foreign('invitation_id')->references('id')->on('oparl_files');

            $table->unsignedInteger('results_protocol_id')->nullable();
            $table->foreign('results_protocol_id')->references('id')->on('oparl_files');

            $table->unsignedInteger('verbatim_protocol_id')->nullable();
            $table->foreign('verbatim_protocol_id')->references('id')->on('oparl_files');

            // TODO: auxiliaryFile is n:n
            // TODO: agendaItem is n:1

            // TODO: keyword is n:n
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
