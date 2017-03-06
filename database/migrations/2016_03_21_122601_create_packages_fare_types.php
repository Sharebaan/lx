<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesFareTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('packages_fare_types', function (Blueprint $table) {
          $table->integer('id_package');
          $table->integer('id_fare_type')->unsigned();
          $table->string('soap_client')->default('HO');
          //$table->primary(['id_package','id_fare_type','soap_client']);
          //$table->index('id_fare_type');
      });
      DB::statement('ALTER TABLE  `packages_fare_types`
        ADD PRIMARY KEY (  `id_package` ,  `id_fare_type` ,  `soap_client`) ;');

        DB::statement('CREATE INDEX  `id_fare_type`
        ON packages_fare_types ( `id_fare_type`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages_fare_types');
    }
}
