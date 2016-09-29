<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKeywordsFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_keywords_files', function (Blueprint $table) {
            $table->unsignedInteger('keyword_id');
            $table->foreign('keyword_id')->references('id')->on('oparl_keywords');

            $table->unsignedInteger('file_id');
            $table->foreign('file_id')->references('id')->on('oparl_files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_keywords_files');
    }
}
