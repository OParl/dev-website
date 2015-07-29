<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('posts', function (Blueprint $table) {
      $table->increments('id');

      $table->timestamps();
      $table->dateTime('published_at')->nullable()->default(null);

      $table->string('title');
      $table->string('slug');

      $table->string('content');

      $table->integer('author_id')->nullable();
      $table->foreign('author_id')->references('id')->on('users');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('posts');
  }
}
