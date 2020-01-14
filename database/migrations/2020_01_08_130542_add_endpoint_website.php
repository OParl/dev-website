<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEndpointWebsite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endpoint_bodies', function (Blueprint $table) {
            if (!Schema::hasColumn('endpoint_bodies', 'website')) {
                $table->string('website')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('endpoint_bodies', function (Blueprint $table) {
            $table->dropColumn(['website']);
        });
    }
}
