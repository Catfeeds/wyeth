<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexsToUserRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_relations', function (Blueprint $table) {

            $table->string('openid', 50)->change();

            // 增加唯一索引
            $table->unique(['openid', 'type']);
            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_relations', function (Blueprint $table) {
            //
        });
    }
}
