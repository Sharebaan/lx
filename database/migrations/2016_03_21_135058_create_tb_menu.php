<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tb_menu', function (Blueprint $table) {
          $table->increments('menu_id');
          $table->integer('parent_id');
          $table->string('module',50);
          $table->string('url',100);
          $table->string('menu_name',100);
          $table->char('menu_type',10);
          $table->string('role_id',100);
          $table->smallInteger('deep');
          $table->integer('ordering')->default(null);
          $table->enum('position',['top','sidebar','both']);
          $table->string('menu_icons',30);
          $table->enum('active',['0','1']);
          $table->text('access_data');
          $table->enum('allow_guest',['0','1']);
          $table->text('menu_lang');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_menu');
    }
}
