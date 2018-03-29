<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderToAdvertise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertise', function (Blueprint $table) {
            $table->integer('order')->comment('显示顺序')->default(0)->after('display');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertise', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
