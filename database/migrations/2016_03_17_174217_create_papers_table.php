<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_papers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            // oparl.id is not in the db layer
            // type is not in the db layer

            $table->unsignedInteger('body_id')->nullable();
            $table->foreign('body_id')->references('id')->on('oparl_bodies');

            $table->string('name')->nullable();
            $table->string('reference')->nullable();

            $table->dateTime('published_date')->nullable();

            $table->string('paper_type')->nullable();

            // TODO: related paper is n:n
            // TODO: superordinated paper is n:n
            // TODO: subordinated paper is n:n

            $table->unsignedInteger('main_file_id')->nullable();
            $table->foreign('main_file_id')->references('id')->on('oparl_files');

            // TODO: auxilary file is n:n
            // TODO: location is n:n
            // TODO: originator_person is n:n
            // TODO: under_direction_of is n:n
            // TODO: originator_organization is n:n
            // TODO: consultation is 1:n

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
        Schema::drop('oparl_papers');
    }
}
