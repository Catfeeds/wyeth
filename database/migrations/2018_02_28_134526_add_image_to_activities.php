<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageToActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('activities', function (Blueprint $table) {
            $table->integer('order')->default(1)->after('status')->comment('顺序,数字小的先显示');
            $table->string('image')->default('')->after('desc')->comment('活动图片');
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
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropColumn('image');
        });
    }
}
