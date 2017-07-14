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
            $table->integer('post_id')->index()->unsigned()->comment('for post_id');
            $table->integer('to_user_id')->index()->unsigned()->comment('post owner');
            $table->integer('user_id')->index()->unsigned()->comment('who commented this post');
            $table->string('content')->comment('content of comment');
            $table->integer('reply_to_user_id')->unsigned()->default(0)->comment('reply someone in comments');
            $table->integer('floor')->unsigned()->default(0)->comment('belongs to which comment_id'); // 备用数据
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
