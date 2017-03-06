<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HotelsSearchCached extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('hotels_search_cached', function (Blueprint $table) {
          $table->increments('id')->unsigned();
          $table->integer('hotel');
          $table->integer('destination');
          $table->date('check_in');
          $table->integer('stay');
          $table->integer('min_stars');
          $table->longText('rooms');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hotels_search_cached');
    }
}
