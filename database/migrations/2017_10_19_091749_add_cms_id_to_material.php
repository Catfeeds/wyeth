<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCmsIdToMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('materiel', function (Blueprint $table) {
            $table->integer('cms_id')->after('name')->comment('CMS关联文章的id');
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
        Schema::table('materiel', function (Blueprint $table) {
            $table->dropColumn('cms_id');
        });
    }
}
