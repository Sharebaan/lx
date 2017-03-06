<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbImport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tb_import', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->string('soap_client')->default('HO');
          $table->text('description');
          $table->string('file');
          $table->dateTime('date');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_import');
    }
}
