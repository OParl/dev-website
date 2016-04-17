<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oparl_files', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            // oparl.id is not in the db layer
            // type is not in the db layer

            $table->string('storage_file_name'); // NOTE: This is not part of OParl, but of this implementation

            $table->string('file_name')->nullable();
            $table->string('name')->nullable();
            $table->string('mime_type')->nullable();

            // TODO: oparl.date is dynamic

            $table->unsignedBigInteger('size')->nullable();
            $table->string('sha1_checksum')->nullable();
            $table->string('text')->nullable();

            // TODO: access_url is dynamic
            // TODO: download_url is dynamic

            $table->string('external_service_url')->nullable();

            $table->unsignedInteger('master_file_id')->nullable();
            $table->foreign('master_file_id')->references('id')->on('oparl_files');

            // TODO: derivative_file is n:n

            $table->string('license')->nullable();
            $table->string('file_license')->nullable();

            // TODO: meeting is n:n
            // TODO: agenda_item is n:n
            // TODO: paper is n:n

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oparl_files');
    }
}
