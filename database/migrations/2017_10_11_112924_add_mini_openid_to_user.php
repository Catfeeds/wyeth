<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMiniOpenidToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'mini_openid')){
                $table->string('mini_openid')->comment('小程序openid')->after('openid');
                $table->index('mini_openid');
                $table->index('unionid');
            }
            if (!Schema::hasColumn('user', 'pregdate')){
                $table->date('pregdate')->comment('孕期提醒的宝宝生日')->after('baby_birthday');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('mini_openid');
            $table->dropColumn('pregdate');
        });
    }
}
