<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePapersSubordinatedPapers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_papers_subordinated_papers', function (Blueprint $table) {
            $table->unsignedInteger('paper_id');
            $table->foreign('paper_id')->references('id')->on('oparl_papers');

            $table->unsignedInteger('subordinated_paper_id');
            $table->foreign('subordinated_paper_id')->references('id')->on('oparl_papers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::drop('oparl_papers_subordinated_papers');
    }
}
