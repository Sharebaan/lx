<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRomcardOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('romcard_orders', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('rezervare_id');
          $table->string('ORDER');
          $table->string('AMOUNT');
          $table->string('CURRENCY');
          $table->string('RRN');
          $table->string('INT_REF');
          $table->string('TERMINAL');
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
        //
    }
}
