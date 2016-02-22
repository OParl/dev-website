<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
      $table->integer('author_id', false, true)->nullable();
      $table->foreign('author_id')->references('id')->on('users');

      $table->string('author_email')->nullable();
      $table->string('author_name')->nullable();

      $table->text('content');

      $table->enum('status', ['ham', 'spam', 'unvalidated'])->default('unvalidated');

      $table->integer('post_id', false, true)->nullable();
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
