<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticAdSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_ad_summary', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand')->comment('广告品牌');
            $table->dateTime('start_day')->comment('开始时间-以周为单位');
            $table->dateTime('end_day')->comment('结束时间');
            $table->integer('click_pv');
            $table->integer('click_uv');
            $table->integer('week_pv');
            $table->integer('week_uv');
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
        Schema::drop("statistic_ad_summmary");
    }
}
