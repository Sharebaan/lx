<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plata_rezervare', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_bookedpackage');
          $table->string('suma');
          $table->string('payment_type',10);
          $table->string('lname');
          $table->string('fname');
          $table->string('address');
          $table->string('city');
          $table->string('zone');
          $table->string('country');
          $table->string('email');
          $table->string('phone');
          $table->string('company_name');
          $table->string('company_address');
          $table->string('company_city');
          $table->string('company_zone');
          $table->string('company_country');
          $table->string('company_nrc');
          $table->string('company_cui');
          $table->string('company_bank_account');
          $table->string('company_bank');
          $table->string('options_newsletter');
          $table->string('soap_client');
          $table->string('paytype');
          $table->tinyInteger('options_newsletter');
          $table->tinyInteger('rezervare');
          $table->tinyInteger('achitat');
          $table->tinyInteger('refuzat');
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
