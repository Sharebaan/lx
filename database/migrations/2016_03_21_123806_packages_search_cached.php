<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackagesSearchCached extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('packages_search_cached', function (Blueprint $table) {
          $table->increments('id')->unsigned();
          $table->tinyInteger('is_tour');
          $table->tinyInteger('is_bus');
          $table->tinyInteger('is_flight');
          $table->integer('departure');
          $table->integer('destination');
          $table->integer('hotel');
          $table->integer('duration');
          $table->integer('min_stars');
          $table->date('departure_date');
          $table->longText('rooms')->nullable();
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
        Schema::drop('packages_search_cached');
    }
}
