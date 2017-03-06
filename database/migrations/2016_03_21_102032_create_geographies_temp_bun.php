<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeographiesTempBun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('geographies_temp_bun', function (Blueprint $table) {
          $table->integer('id');
          $table->integer('id_parent');
          $table->string('min_val');
          $table->string('max_val');
          $table->string('soap_client');
          $table->string('name');
          $table->string('int_name');
          $table->string('child_label');
          $table->longText('description');
          $table->integer('tree_level');
          //$table->primary(['id','soap_client']);
      });
      DB::statement('ALTER TABLE  `geographies_temp_bun` 
        ADD PRIMARY KEY (  `id` ,  `soap_client` ) ;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geographies_temp_bun');
    }
}
