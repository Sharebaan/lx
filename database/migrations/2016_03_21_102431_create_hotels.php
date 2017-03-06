<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('hotels', function (Blueprint $table) {
          $table->increments('idx');
          $table->integer('id')->nullable();
          $table->string('code')->nullable();
          $table->string('name')->nullable();
          $table->integer('class')->nullable();
          $table->string('description')->nullable();
          $table->string('address')->nullable();
          $table->string('zip')->nullable();
          $table->string('phone')->nullable();
          $table->string('fax')->nullable();
          $table->integer('location');
          $table->string('url')->nullable();
          $table->float('latitude',8,2)->nullable();
          $table->float('longitude',8,2)->nullable();
          $table->string('extra_class')->nullable();
          $table->tinyInteger('use_individually');
          $table->tinyInteger('use_on_packages')->nullable();
          $table->string('soap_client');
          $table->tinyInteger('update');

          $table->dateTime('createdOn');
          $table->dateTime('updatedOn');

          $table->integer('is_local')->nullable();
          $table->tinyInteger('available')->default(1);

          //$table->index('id');
      });
      DB::statement('CREATE INDEX  `id`
      ON hotels ( `id`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hotels');
    }
}
