<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKeywordsConsultations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_keywords_consultations', function (Blueprint $table) {
            $table->unsignedInteger('keyword_id');
            $table->foreign('keyword_id')->references('id')->on('oparl_keywords');

            $table->unsignedInteger('consultation_id');
            $table->foreign('consultation_id')->references('id')->on('oparl_consultations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_keywords_consultations');
    }
}
