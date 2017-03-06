<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackagesSearchCachedPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('packages_search_cached_prices', function (Blueprint $table) {
          $table->increments('id')->unsigned();
          $table->integer('id_package_search');
          $table->integer('id_package');
          $table->integer('id_room_category');
          $table->integer('id_meal_plan');
          $table->integer('gross');
          $table->integer('vat');
          $table->integer('tax');
          $table->string('soap_client')->default('HO');

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
        Schema::drop('packages_search_cached_prices');
    }
}
