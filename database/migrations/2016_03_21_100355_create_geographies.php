<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeographies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('geographies', function (Blueprint $table) {
          $table->integer('id');
          $table->string('int_name');
          $table->string('child_label')->nullable();
          $table->longText('description')->nullable();
          $table->integer('tree_level')->nullable();
          $table->integer('id_parent')->nullable();
          $table->string('name');
          $table->string('min_val')->nullable();
          $table->string('max_val')->nullable();
          $table->longText('availability')->nullable();
          $table->tinyInteger('available_in_stays')->nullable();
          $table->tinyInteger('available_in_circuits')->nullable();
          $table->primary('id');
          //$table->index('id_parent');
      });
      DB::statement('CREATE INDEX  `id_parent`
      ON geographies ( `id_parent`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geographies');
    }
}
