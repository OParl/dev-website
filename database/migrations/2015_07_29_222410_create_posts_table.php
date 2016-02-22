<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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

      $table->integer('author_id', false, true)->nullable();
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
