<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePapersLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_papers_locations', function (Blueprint $table) {
            $table->unsignedInteger('paper_id');
            $table->foreign('paper_id')->references('id')->on('oparl_papers');

            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('oparl_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_papers_locations');
    }
}
