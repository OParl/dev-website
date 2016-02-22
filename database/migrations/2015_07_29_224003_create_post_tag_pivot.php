<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostTagPivot extends Migration
{
    /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      Schema::create('post_tag', function (Blueprint $table) {
      $table->integer('post_id', false, true);
      $table->integer('tag_id', false, true);

      $table->foreign('post_id')->references('id')->on('posts');
      $table->foreign('tag_id')->references('id')->on('tags');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      Schema::drop('post_tag');
  }
}
