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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamps();
            $table->dateTime('published_at')->nullable()->default(null);

            $table->string('title');
            $table->string('slug');

            $table->string('content');

            $table->integer('author_id', false, true)->nullable();
            $table->foreign('author_id')->references('id')->on('users');
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamps();

            $table->string('name')->unique();
            $table->string('slug')->unique();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->integer('post_id', false, true);
            $table->integer('tag_id', false, true);

            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('tag_id')->references('id')->on('tags');
        });

        Schema::create('environment_variables', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('key')->unique();
            $table->json('value');
        });

        $prefix = 'newsletter_'; // was config('newsletter.prefix');

        Schema::create($prefix.'messages', function (Blueprint $table) use ($prefix) {
            $table->increments('id');
            $table->timestamps();
            $table->string('subject');
            $table->text('message');

            $table->integer('subscription_id', false, true)->nullable();
            $table->foreign('subscription_id')->references('id')->on($prefix.'subscriptions');

            $table->dateTime('sent_on')->nullable();
        });

        Schema::create($prefix.'subscribers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('company')->nullable();
        });

        Schema::create($prefix.'subscribers_subscriptions', function (Blueprint $table) {
            $table->integer('subscriber_id', false, true);
            $table->integer('subscription_id', false, true);

            $table->foreign('subscriber_id')->references('id')->on($prefix.'subscribers');
            $table->foreign('subscription_id')->references('id')->on($prefix.'subscriptions');
        });
    }
}
