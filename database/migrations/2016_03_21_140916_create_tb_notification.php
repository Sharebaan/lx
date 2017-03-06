<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tb_notification', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('userid');
          $table->string('url');
          $table->string('title');
          $table->text('note');
          $table->dateTime('created');
          $table->char('icon',20);
          $table->enum('is_read',['0','1']);

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_notification');
    }
}
