<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tb_groups', function (Blueprint $table) {

          $table->mediumInteger('group_id',8)->unsigned();
          $table->string('name',20);
          $table->string('description',100);
          $table->integer('level');
          //$table->primary('group_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_groups');
    }
}
