<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexsToUserCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_course', function (Blueprint $table) {
            // 增加索引
            $table->index('cid');
            $table->index('uid');
        });

        Schema::table('message', function (Blueprint $table) {
            // 增加索引
            $table->index('source_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_course', function (Blueprint $table) {
            $table->dropIndex('cid');
            $table->dropIndex('uid');
        });

        Schema::table('message', function (Blueprint $table) {
            $table->dropIndex('source_id');
        });
    }
}
