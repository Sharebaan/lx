<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbGroupsAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tb_groups_access', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('group_id');
          $table->integer('module_id');
          $table->text('access_data');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_groups_access');
    }
}
