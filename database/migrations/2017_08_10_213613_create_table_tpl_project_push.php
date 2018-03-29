<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTplProjectPush extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpl_project_push', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->comment('推送项目id')->default(0);
            $table->integer('type')->comment('模板消息类型')->default(0);
            $table->string('openid')->comment('openid')->default('');
            $table->tinyInteger('status')->comment('推送是否成功')->default(0);
            $table->string('abtest')->comment('abtest')->default('');
            $table->timestamps();
            $table->index('pid');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tpl_project_push');
    }
}
