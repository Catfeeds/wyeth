<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUserTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('user_tags', function ($table) {
            $table->integer('type')->comment('tag类型 0:内容tag 1:孕期tag 2:讲师tag')->default(0)->after('tid');
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
        Schema::table('user_tags', function ($table) {
            $table->dropColumn('type');
        });
    }
}
