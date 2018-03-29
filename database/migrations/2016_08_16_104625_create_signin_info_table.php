<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigninInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signin_game_configs', function ($table) {
            $table->increments('id');
            $table->integer('cid')->unsigned()->comment('课程ID');
            $table->tinyInteger('platfrom')->comment('所属平台 1 微信  2 QQ');
            $table->integer('win_num')->comment('获奖人数');
            $table->string('fri_share_title', 100)->comment('好友分享标题');
            $table->string('fri_share_desc', 150)->comment('好友分享描述');
            $table->string('fri_circle_share_title', 150)->comment('朋友圈分享语');
            $table->string('share_img', 250)->comment('分享图片');
            $table->string('brand_img', 250)->comment('品牌按钮 图片1');
            $table->string('rule_img', 250)->comment('游戏规则 图片2');
            $table->string('intro_img', 250)->comment('产品介绍 图片3');
            $table->string('teacher_img', 250)->comment('课程讲师介绍 图片4');
            $table->string('living_img', 250)->comment('签到回直播按钮 图片5');
            $table->string('prize_img', 250)->comment('奖品图片 图片6');
            $table->string('award_img', 250)->comment('领奖按钮 图片7');
            $table->string('user_info_title', 250)->comment('用户信息页头 图片8');
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
        //
    }
}
