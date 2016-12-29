<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LegislativeTermAddLicense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('oparl_legislative_terms', function (Blueprint $table) {
            $table->string('license')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('oparl_legislative_terms', function (Blueprint $table) {
            $table->dropColumn('license');
        });
    }
}
