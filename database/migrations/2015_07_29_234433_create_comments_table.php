<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('comments', function (Blueprint $table) {
      $table->increments('id');
      $table->timestamps();

      // if the author is a user, don't redundantly save the information
      $table->integer('author_id')->nullable();
      $table->foreign('author_id')->references('id')->on('users');

      $table->string('author_email');
      $table->string('author_name');

      $table->text('content');

      $table->enum('status', ['ham', 'spam', 'unvalidated'])->default('unvalidated');

      $table->integer('post_id')->nullable();
      $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('comments');
  }
}
