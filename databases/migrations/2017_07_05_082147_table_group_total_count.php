<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGroupTotalCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_count', function(Blueprint $table) {
            $table->increments('id')->comment('primary key');
            $table->integer('group_id')->unsigned()->comment('which group');
            $table->integer('posts_count')->unsigned()->default(0)->comment('all posts');
            $table->integer('members_count')->unsigned()->default(0)->comment('all member');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups_count');
    }
}
