<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id')->comment('primary key');
            $table->string('title', 10)->index()->comment('title of group');
            $table->string('intro')->index()->default('')->comment('description fo group');
            $table->tinyInteger('is_audit')->default(0)->comment('audit status for group');
            $table->string('group_client_ip')->nullable()->default('::1')->comment('申请ip');
            $table->integer('posts_count')->default(0)->comment('total posts of group');
            $table->integer('members_count')->default(1)->comment('totle members of group');
            $table->unsignedBigInteger('group_mark')->comment('group_mark for group created');
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
        Schema::dropIfExists('groups');
    }
}
