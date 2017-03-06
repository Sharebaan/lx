<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tb_pages', function (Blueprint $table) {
          $table->increments('pageID');
          $table->string('title');
          $table->string('alias',100);
          $table->text('note');
          $table->dateTime('created');
          $table->dateTime('updated');
          $table->string('filename',50);
          $table->enum('status',['enable','disable']);
          $table->text('access');
          $table->enum('allow_guest',['0','1']);
          $table->enum('template',['frontend','backend']);
          $table->string('metakey');
          $table->text('metadesc');

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_pages');
    }
}
