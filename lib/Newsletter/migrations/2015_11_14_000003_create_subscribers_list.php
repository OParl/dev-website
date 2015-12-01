<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribersList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('newsletter.prefix');

        Schema::create($prefix . 'subscriber_subscriptions', function (Blueprint $table) use ($prefix) {
            $table->integer('subscriber_id', false, true);
            $table->integer('subscription_id', false, true);

            $table->foreign('subscriber_id')->references('id')->on($prefix . 'subscribers');
            $table->foreign('subscription_id')->references('id')->on($prefix . 'subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('newsletter.prefix');

        Schema::drop($prefix . 'subscriber_subscriptions');
    }
}
