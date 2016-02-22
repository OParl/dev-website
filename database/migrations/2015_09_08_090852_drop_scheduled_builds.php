<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DropScheduledBuilds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('scheduled_builds');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('scheduled_builds', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('version');
            $table->string('email');
            $table->string('format');
        });
    }
}
