<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTplProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //创建推送项目
        Schema::create('tpl_project', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('推送项目名')->default('');
            $table->string('notify_title')->comment('模板消息标题')->default('');
            $table->string('notify_content')->comment('模板消息内容')->default('');
            $table->string('notify_odate')->comment('模板消息时间')->default('');
            $table->string('notify_address')->comment('模板消息地址')->default('');
            $table->string('notify_remark')->comment('模板消息备注')->default('');
            $table->text('notify_url')->comment('模板消息url')->default('');
            $table->tinyInteger('notify_template_id')->comment('模板消息类型')->default(0);
            $table->text('remark')->comment('备注')->default('');
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
        Schema::drop('tpl_project');
    }
}
