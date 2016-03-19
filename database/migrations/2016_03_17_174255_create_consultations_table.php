<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_consultations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            // oparl.id is not in the db layer
            // type is not in the db layer

            $table->unsignedInteger('paper_id')->nullable();
            $table->foreign('paper_id')->references('id')->on('oparl_papers');

            $table->unsignedInteger('agenda_item_id')->nullable();
            $table->foreign('agenda_item_id')->references('id')->on('oparl_agenda_items');

            $table->unsignedInteger('meeting_id')->nullable();
            $table->foreign('meeting_id')->references('id')->on('oparl_meetings');

            // TODO: organization is n:n

            $table->boolean('authoritative')->nullable();
            $table->string('role')->nullable();

            // TODO: keyword is n:n
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_consultations');
    }
}
