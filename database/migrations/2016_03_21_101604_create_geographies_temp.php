<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeographiesTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('geographies_temp', function (Blueprint $table) {
          $table->increments('idx');
          $table->integer('id');
          $table->integer('id_parent')->nullable();
          $table->string('min_val')->nullable();
          $table->string('max_val')->nullable();
          $table->string('soap_client');
          $table->string('name')->nullable();
          $table->string('int_name')->nullable();
          $table->string('child_label')->nullable();
          $table->longText('description')->nullable();
          $table->integer('tree_level')->nullable();

          //$table->index('id');
      });
      DB::statement('CREATE INDEX  `id`
      ON geographies_temp ( `id`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geographies_temp');
    }
}
