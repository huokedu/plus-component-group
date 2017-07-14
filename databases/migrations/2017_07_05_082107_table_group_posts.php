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
            $table->text('content')->comment('content of post');
            $table->integer('group_id')->index()->unsigned()->comment('belongsto which group');
            $table->integer('views')->unsigned()->default(1)->comment('views of post');
            $table->integer('diggs')->unsigned()->default(0)->comment('diggs number of post');
            $table->integer('collections')->unsigned()->default(0)->comment('collections number of post');
            $table->integer('comments')->default(0)->comment('comments number of post');
            $table->integer('user_id')->index()->unsigned()->comment('who add the post');
            $table->tinyInteger('is_audit')->default(1)->comment('1 audited, 0 unaudited');
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
