<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tb_module', function (Blueprint $table) {
          $table->increments('module_id');
          $table->string('module_name',100);
          $table->string('module_title',100);
          $table->string('module_note');
          $table->string('module_author',100);
          $table->timestamp('module_created');
          $table->text('module_desc');
          $table->string('module_db');
          $table->string('module_db_key',100);
          $table->enum('module_type',['master','report','proccess','core','gene']);
          $table->longText('module_config');
          $table->text('module_lang');

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_module');
    }
}
