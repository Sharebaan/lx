<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Remake2BookingPackageSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('booking_package_searches', function (Blueprint $table) {
        $table->string('id_hotel');
        $table->tinyInteger('is_tour');
        $table->tinyInteger('is_bus');
        $table->tinyInteger('is_flight');
        $table->string('destination');
        $table->string('soap_client')->default('HO');
        $table->string('departure_point');
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
