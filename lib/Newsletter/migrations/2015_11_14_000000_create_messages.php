<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('newsletter.prefix');

        Schema::create($prefix . 'messages', function (Blueprint $table) use ($prefix) {
            $table->increments('id');
            $table->timestamps();
            $table->string('subject');
            $table->text('message');

            $table->integer('subscription_id', false, true)->nullable();
            $table->foreign('subscription_id')->references('id')->on($prefix . 'subscriptions');

            $table->dateTime('sent_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
