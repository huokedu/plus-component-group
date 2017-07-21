<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGroupManagers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_managers', function(Blueprint $table) {
            $table->increments('id')->comment('primary keys');
            $table->unsignedInteger('group_id')->index()->comment('manager of groups id');
            $table->unsignedInteger('user_id')->comment('user_id of manager');
            $table->unsignedTinyinteger('founder')->default(0)->comment('if user_id be a founder');
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
        Schema::dropIfExists('group_managers');
    }
}
