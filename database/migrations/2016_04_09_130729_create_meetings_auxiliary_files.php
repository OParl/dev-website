<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsAuxiliaryFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_meetings_auxiliary_files', function (Blueprint $table) {
            $table->unsignedInteger('meeting_id');
            $table->foreign('meeting_id')->references('id')->on('oparl_meetings');

            $table->unsignedInteger('file_id');
            $table->foreign('file_id')->references('id')->on('oparl_files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_meetings_auxiliary_files');
    }
}
