<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePapersOriginatorPeople extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_papers_originator_people', function (Blueprint $table) {
            $table->unsignedInteger('paper_id');
            $table->foreign('paper_id')->references('id')->on('oparl_papers');

            $table->unsignedInteger('person_id');
            $table->foreign('person_id')->references('id')->on('oparl_people');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_papers_originator_people');
    }
}
