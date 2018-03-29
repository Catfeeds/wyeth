<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddXxjpTitleToCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //添加自动下行的标题字段
        Schema::table('course', function (Blueprint $table) {
            $table->string('xxjp_title')->default('')->comment('慧摇自动下行精品课的标题');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->dropColumn('xxjp_title');
        });
    }
}
