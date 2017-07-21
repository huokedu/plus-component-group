<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGroupSearchKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_search_keys', function (Blueprint $table) {
            $table->increments('key_id')->comment('primary key');
            $table->string('keyword')->comment('keyword');
            $table->integer('count')->unsigned()->default(1)->comment('search time');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('keyword');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_search_keys');
    }
}
