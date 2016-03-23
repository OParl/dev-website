<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_people', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            // oparl.id is not in the db layer
            // type is not in the db layer

            $table->unsignedInteger('body_id')->nullable();
            $table->foreign('body_id')->references('id')->on('bodies');

            // name is not in the db layer

            $table->string('family_name')->nullable();
            $table->string('given_name')->nullable();
            $table->string('form_of_address')->nullable();
            $table->string('affix')->nullable();

            // TODO: title is 1:n

            $table->string('gender')->nullable();

            // TODO: phone number is 1:n
            // TODO: email is 1:n

            $table->string('street_address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('sub_locality')->nullable();
            $table->string('locality')->nullable();

            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('oparl_locations');

            // TODO: status is n:n
            // TODO: membership is n:n

            $table->string('life')->nullable();
            $table->string('life_source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_people');
    }
}
