<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobilpayOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('mobilpay_orders', function (Blueprint $table) {
          $table->integer('id');
        //  $table->dropPrimary('id');
          $table->string('id_booking');
          $table->text('input');
          $table->string('status');
          $table->text('booking_response');
          $table->string('code');
          $table->timestamps();
      });
    //  DB::statement('ALTER TABLE  `mobilpay_orders`,
    //    ADD PRIMARY KEY (  `id` ) ;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mobilpay_orders');
    }
}
