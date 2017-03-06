<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesPriceTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('packages_price_types', function (Blueprint $table) {
          $table->integer('id_package');
          $table->integer('id_price_type')->unsigned();
          $table->string('soap_client')->default('HO');
          //$table->primary(['id_package','id_price_type','soap_client']);
          //$table->index('id_price_set');
      });
      DB::statement('ALTER TABLE  `packages_price_types`
        ADD PRIMARY KEY (  `id_package` ,  `id_price_type` ,  `soap_client`) ;');
        DB::statement('CREATE INDEX  `id_price_type`
        ON packages_price_types ( `id_price_type`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages_price_types');
    }
}
