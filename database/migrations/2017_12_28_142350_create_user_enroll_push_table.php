<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEnrollPushTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_enroll_push', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户id');
            $table->string('openid')->comment('openid');
            $table->integer('cid')->comment('课程id');
            $table->dateTime('push_time')->comment('推送时间');
            $table->integer('status')->comment('推送状态 1推送成功 -1推送失败');
            $table->timestamps();
            $table->index('cid');
            $table->index('uid');
            $table->index(['uid', 'cid']);
            $table->index(['push_time', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_enroll_push');
    }
}
