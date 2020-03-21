<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWikidataAndOsmId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endpoints', function (Blueprint $blueprint) {
            $blueprint->string('wikidata', 20)->nullable();
            $blueprint->integer('osm')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('endpoints', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['wikidata', 'osm']);
        });
    }
}
