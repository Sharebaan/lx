<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailedDescriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('detailed_descriptions', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_hotel')->nullable();
          $table->longText('text')->nullable();
          $table->integer('index')->nullable();
          $table->integer('id_package')->nullable();
          $table->string('soap_client')->nullable();
          $table->string('label')->nullable();
          $table->tinyInteger('update')->nullable();

          //$table->index(['id_hotel', 'id_package']);
      });
      DB::statement('CREATE INDEX  `id_hotel`
      ON detailed_descriptions ( `id_hotel`);');
      DB::statement('CREATE INDEX  `id_package`
      ON detailed_descriptions ( `id_package`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('detailed_descriptions');
    }
}
