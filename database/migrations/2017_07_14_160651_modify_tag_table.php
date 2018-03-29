<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tags', function ($table) {
            $table->integer('type')->comment('tag类型 0:内容tag 1:孕期tag 2:讲师tag')->default(0)->after('name');
            $table->integer('img')->comment('tag图标url')->nullable()->after('type');
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
    }
}
