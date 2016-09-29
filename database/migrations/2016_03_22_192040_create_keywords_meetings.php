<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKeywordsMeetings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_keywords_meetings', function (Blueprint $table) {
            $table->unsignedInteger('keyword_id');
            $table->foreign('keyword_id')->references('id')->on('oparl_keywords');

            $table->unsignedInteger('meeting_id');
            $table->foreign('meeting_id')->references('id')->on('oparl_meetings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_keywords_meetings');
    }
}
