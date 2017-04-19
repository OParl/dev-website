<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePapersOriginatorOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_papers_originator_organizations', function (Blueprint $table) {
            $table->unsignedInteger('paper_id');
            $table->foreign('paper_id')->references('id')->on('oparl_papers');

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
        Schema::drop('oparl_papers_originator_people');
    }
}
