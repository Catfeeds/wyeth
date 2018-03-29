<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('cid')->comment('课程id,没有则为0');
            $table->string('type')->comment('任务类型');
            $table->integer('mq')->comment('任务增加多少mq');
            $table->integer('get')->comment('是否领取奖励');
            $table->timestamps();
            $table->index('uid');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('task');
    }
}
