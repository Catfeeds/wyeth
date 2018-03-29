<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTidToTradeIdInUserBuyCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_buy_courses', function ($table) {
            $table->renameColumn('tid', 'trade_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_buy_courses', function ($table) {
            $table->renameColumn('trade_id', 'tid');
        });
    }
}
