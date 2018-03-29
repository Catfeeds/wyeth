<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceToCourseCat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_cat', function (Blueprint $table) {
            $table->integer('price')->comment('套课价格')->default(0)->after('img');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_cat', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
}
