<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tb_users', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('group_id');
          $table->string('username',100);
          $table->string('password',64);
          $table->string('email',100);
          $table->string('first_name',50);
          $table->string('last_name',50);
          $table->string('avatar',100);
          $table->tinyInteger('active')->unsigned();
          $table->tinyInteger('login_attempt');
          $table->dateTime('last_login');
          $table->string('reminder',64);
          $table->string('activation',50);
          $table->string('remember_token',100);
          $table->integer('last_activity');
          $table->timestamps();

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_users');
    }
}
