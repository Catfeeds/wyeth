<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCatTitleDesToCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->string('cat_desc')->comment('套课页面显示简介')->default('')->after('teacher_desc');
            $table->string('cat_title')->comment('套课页面显示标题')->default('')->after('teacher_desc');
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
            $table->dropColumn('cat_desc');
            $table->dropColumn('cat_title');
        });
    }
}
