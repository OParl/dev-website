<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSpecificationBuilds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('specification_builds');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('specification_builds', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->boolean('queried');
            $table->string('hash');
            $table->string('human_version');
            $table->string('commit_message');
            $table->boolean('persistent');
            $table->boolean('displayed');
        });
    }
}
