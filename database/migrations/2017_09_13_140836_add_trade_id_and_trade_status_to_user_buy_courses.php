<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTradeIdAndTradeStatusToUserBuyCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_buy_courses', function (Blueprint $table) {
            $table->integer('trade_status')->comment('交易状态 0：待支付；1：已支付；')->default(0)->after('cid');
            $table->string('tid', 16)->comment('订单ID')->default('')->after('id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_buy_courses', function (Blueprint $table) {
            $table->dropColumn('trade_status');
            $table->dropColumn('trade_id');
        });
    }
}
