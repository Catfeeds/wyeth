<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBindTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bind', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('微课堂id');
            $table->integer('other_id')->comment('第三方绑定账号id');
            $table->timestamps();
            $table->index('uid');
            $table->index('other_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_bind');
    }
}
