<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgendaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_agenda_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            // oparl.id is not in the db layer
            // type is not in the db layer

            $table->unsignedInteger('meeting_id')->nullable();
            $table->foreign('meeting_id')->references('id')->on('oparl_meetings');

            $table->string('number')->nullable();
            $table->string('name')->nullable();
            $table->boolean('public')->nullable();

            $table->unsignedInteger('consultation_id')->nullable();
            $table->foreign('consultation_id')->references('id')->on('oparl_consultations');

            $table->string('result')->nullable();
            $table->string('resolutionText')->nullable();

            $table->unsignedInteger('resolution_file_id')->nullable();
            $table->foreign('resolution_file_id')->references('id')->on('oparl_files');

            // TODO: auxiliary file is n:n

            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_agenda_items');
    }
}
