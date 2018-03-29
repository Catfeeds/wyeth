<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('活动名称');
            $table->string('type')->comment('活动类型');
            $table->string('desc')->comment('活动描述');
            $table->text('setting')->comment('活动配置');
            $table->string('view')->comment('活动视图');
            $table->integer('crm')->default(0)->comment('是否需要注册crm会员 0不需要 1需要');
            $table->integer('status')->default(0)->comment('活动状态 0正常');
            $table->dateTime('start')->comment('开始时间');
            $table->dateTime('end')->comment('结束时间');
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
        Schema::drop('activities');
    }
}
