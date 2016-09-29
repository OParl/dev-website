<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKeywordsLegislativeTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_keywords_legislative_terms', function (Blueprint $table) {
            $table->unsignedInteger('keyword_id');
            $table->foreign('keyword_id')->references('id')->on('oparl_keywords');

            $table->unsignedInteger('legislative_term_id');
            $table->foreign('legislative_term_id')->references('id')->on('oparl_legislative_terms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_keywords_legislative_terms');
    }
}
