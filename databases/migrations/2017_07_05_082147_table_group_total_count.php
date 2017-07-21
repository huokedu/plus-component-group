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
            $table->unsignedInteger('group_id')->comment('which group');
            $table->unsignedInteger('posts_count')->default(0)->comment('all posts');
            $table->unsignedInteger('members_count')->default(0)->comment('all member');
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
