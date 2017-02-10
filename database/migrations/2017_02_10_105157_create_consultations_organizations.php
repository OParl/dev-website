<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultationsOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_consultations_organizations', function (Blueprint $table) {
            $table->unsignedInteger('consultation_id');
            $table->foreign('consultation_id')->references('id')->on('oparl_consultations');

            $table->unsignedInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('oparl_organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_consultations_organizations');
    }
}
