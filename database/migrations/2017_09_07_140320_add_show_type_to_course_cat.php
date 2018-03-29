<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShowTypeToCourseCat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_cat', function (Blueprint $table) {
            $table->integer('show_type')->comment('展示类型0：一体化，1：瀑布流，2：讲师')->default(0)->after('img');
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
            $table->dropColumn('show_type');
        });
    }
}
