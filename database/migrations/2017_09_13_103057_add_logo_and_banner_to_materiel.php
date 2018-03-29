<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogoAndBannerToMateriel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materiel', function (Blueprint $table) {
            $table->string('banner')->comment('头图')->default('')->after('name');
            $table->string('platform_logo')->comment('平台logo')->default('')->after('banner');
            $table->string('platform_name')->comment('平台名称')->default('')->after('banner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materiel', function (Blueprint $table) {
            $table->dropColumn('banner');
            $table->dropColumn('platform_logo');
            $table->dropColumn('platform_name');
        });
    }
}
