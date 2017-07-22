<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGroupMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_members', function(Blueprint $table) {
            $table->increments('id')->comment('primary key');
            $table->unsignedInteger('user_id')->index()->comment('member_user_id');
            $table->unsignedInteger('group_id')->index()->comment('group_id of member');
            $table->tinyInteger('is_audit')->unsigned()->default(1)->comment('if user is audited');
            $table->timestamps();

            $table->unique(['user_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_members');
    }
}
