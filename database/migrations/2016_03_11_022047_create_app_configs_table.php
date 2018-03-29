<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_configs', function (Blueprint $table) {

            $table->increments('id');
            $table->string('module', 100)->default('');
            $table->string('key', 50)->default('');
            $table->longText('data');
            $table->integer('displayorder')->default(10);
            $table->timestamps();

            $table->index(['module', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_configs', function (Blueprint $table) {
            Schema::drop('app_configs');
        });
    }
}
