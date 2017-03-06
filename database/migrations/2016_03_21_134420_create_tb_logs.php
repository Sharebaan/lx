<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tb_logs', function (Blueprint $table) {
          $table->increments('auditID');
          $table->string('ipaddress',50);
          $table->integer('user_id');
          $table->string('module',50);
          $table->string('task',50);
          $table->text('note');
          $table->timestamp('logdate');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_logs');
    }
}
