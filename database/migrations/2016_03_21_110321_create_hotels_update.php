<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('hotels_update', function (Blueprint $table) {
          $table->increments('idx');
          $table->integer('idx_hotel');
          $table->string('name')->nullable();
          $table->integer('class')->nullable();
          $table->string('description')->nullable();
          $table->string('address')->nullable();
          $table->string('zip')->nullable();
          $table->string('phone')->nullable();
          $table->string('fax')->nullable();
          $table->integer('location')->nullable();
          $table->string('url')->nullable();
          $table->float('latitude',8,2)->nullable();
          $table->float('longitude',8,2)->nullable();
          $table->string('extra_class')->nullable();
          $table->tinyInteger('use_individually')->nullable();
          $table->tinyInteger('use_on_packages')->nullable();
          $table->dateTime('createdOn');
          $table->dateTime('updatedOn');
          $table->tinyInteger('available')->default(1);

          //$table->index('id');
      });
      //DB::statement('CREATE INDEX  `id`,
      //ON hotels_update ( `id`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hotels_update');
    }
}
