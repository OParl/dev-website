<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePapersAuxiliaryFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_papers_auxiliary_files', function (Blueprint $table) {
            $table->unsignedInteger('paper_id');
            $table->foreign('paper_id')->references('id')->on('oparl_papers');

            $table->unsignedInteger('auxiliary_file_id');
            $table->foreign('auxiliary_file_id')->references('id')->on('oparl_files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_papers_auxiliary_files');
    }
}
