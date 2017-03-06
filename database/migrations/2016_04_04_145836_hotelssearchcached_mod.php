<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HotelssearchcachedMod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('hotels_search_cached', function (Blueprint $table) {

          $table->integer('hotel')->nullable()->change();

          $table->integer('min_stars')->nullable()->change();
          $table->longText('rooms')->nullable()->change();

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
