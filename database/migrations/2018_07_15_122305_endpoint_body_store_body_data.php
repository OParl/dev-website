<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EndpointBodyStoreBodyData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endpoint_bodies', function (Blueprint $table) {
            $table->string('license')->nullable();
            $table->json('json')->nullable();
            $table->string('website')->nullable();
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
            $table->dropColumn(['json', 'license', 'website']);
        });
    }
}
