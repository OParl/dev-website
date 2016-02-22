<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;

class DropDeprecatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();

        Schema::dropIfExists('users');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('environments');

        $prefix = 'newsletter_'; // was config('newsletter.prefix');

        Schema::dropIfExists($prefix.'messages');
        Schema::dropIfExists($prefix.'subscribers');
        Schema::dropIfExists($prefix.'subscribers_subscriptions');
        Schema::dropIfExists($prefix.'subscriptions');

        Model::reguard();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // NOTE: This migration cannot be rolled back, as it is dropping a lot of
        //       deprecated tables. If you want to bring back one of those,
        //       you might want to consider duplicating it's original migration
        //       into a new copy that runs after this one.

        throw new LogicException('This migration cannot be rolled back.');
    }
}
