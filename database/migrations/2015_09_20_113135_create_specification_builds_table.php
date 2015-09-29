<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecificationBuildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specification_builds', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->boolean('queried');
            $table->string('hash');
            $table->string('humanVersion');
            $table->string('commitMessage');
            $table->boolean('persistent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('specification_builds');
    }
}
