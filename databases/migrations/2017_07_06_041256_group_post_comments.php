<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupPostComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_post_comments', function(Blueprint $table) {
            $table->increments('id')->comment('primary key');
            $table->unsignedInteger('post_id')->index()->comment('for post_id');
            $table->unsignedInteger('to_user_id')->index()->comment('post owner');
            $table->unsignedInteger('user_id')->index()->comment('who commented this post');
            $table->string('content')->comment('content of comment');
            $table->unsignedInteger('reply_to_user_id')->default(0)->comment('reply someone in comments');
            $table->unsignedInteger('floor')->default(0)->comment('belongs to which comment_id'); // 备用数据
            $table->unsignedBigInteger('group_post_comment_mark')->comment('group_post_comment_mark for group_post_comment created');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_post_comments');
    }
}
