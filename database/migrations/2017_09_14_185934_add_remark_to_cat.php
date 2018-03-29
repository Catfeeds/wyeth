<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarkToCat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('course_cat', function (Blueprint $table) {
            $table->text('remark')->comment('备注')->default('')->after('show_type');
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
        Schema::table('course_cat', function (Blueprint $table) {
            $table->dropColumn('remark');
        });
    }
}
