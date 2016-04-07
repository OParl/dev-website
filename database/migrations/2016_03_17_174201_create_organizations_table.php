<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            // oparl.id is not in the db layer
            // type is not in the db layer

            $table->unsignedInteger('body_id')->nullable();
            $table->foreign('body_id')->references('id')->on('bodies');

            $table->string('name')->nullable();
            $table->string('shortName')->nullable();

            // TODO: membership is n:n
            // TODO: meeting is 1:n
            // TODO: post is n:n

            $table->unsignedInteger('sub_organization_of')->nullable();
            $table->foreign('sub_organization_of')->references('id')->on('oparl_organizations');

            $table->enum('organization_type', [
                'externes Gremium',
                'Fraktion',
                'Gremium',
                'Institution',
                'Partei',
                'Sonstiges',
                'Verwaltungsbereich',
            ])->nullable();

            $table->string('classification')->nullable();

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->string('website')->nullable();

            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('oparl_locations');

            $table->string('external_body')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_organizations');
    }
}
