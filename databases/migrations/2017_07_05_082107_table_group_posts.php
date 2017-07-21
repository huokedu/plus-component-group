<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGroupPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_posts', function (Blueprint $table) {
            $table->increments('id')->comment('primary key');
            $table->string('title')->index()->comment('title of post');
            $table->text('content')->nullable()->comment('content of post');
            $table->unsignedInteger('group_id')->index()->comment('belongsto which group');
            $table->unsignedInteger('views')->default(1)->comment('views of post');
            $table->unsignedInteger('diggs')->default(0)->comment('diggs number of post');
            $table->unsignedInteger('collections')->default(0)->comment('collections number of post');
            $table->unsignedInteger('comments_count')->default(0)->comment('comments number of post');
            $table->unsignedInteger('user_id')->index()->comment('who add the post');
            $table->tinyInteger('is_audit')->default(1)->comment('1 audited, 0 unaudited');
            $table->unsignedBigInteger('group_post_mark')->comment('group_post_mark for group_post created');
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
        Schema::dropIfExists('group_posts');
    }
}
