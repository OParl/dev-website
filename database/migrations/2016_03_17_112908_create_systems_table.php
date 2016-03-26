<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_systems', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            // oparl.id is not in the db layer
            // type is not in the db layer

            $table->string('oparl_version');

            // other oparl_versions is not in the db layer
            // body is 1:n

            $table->string('name');

            $table->string('contact_email');
            $table->string('contact_name');

            $table->string('website');

            // vendor is not supported in the database layer
            // product is not supported in the database layer
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_systems');
    }
}
