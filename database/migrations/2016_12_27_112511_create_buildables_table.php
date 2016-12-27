<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildables', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->timestamp('built_at');
            $table->string('name');
            $table->string('path');
            $table->string('version');
            $table->enum('destination_os', ['macOS', 'Debian', 'Windows 10'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('buildables');
    }
}
