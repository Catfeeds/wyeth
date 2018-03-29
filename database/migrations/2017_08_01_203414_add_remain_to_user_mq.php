<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemainToUserMq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('user_mq', function ($table) {
            $table->integer('balance')->comment('余额')->default(0)->after('mq');
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
        Schema::table('user_mq', function ($table) {
            $table->dropColumn('balance');
        });
    }
}
