<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCachedPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('cached_prices', function (Blueprint $table) {
          $table->integer('id_package');
          $table->integer('id_room_category');
          $table->integer('id_price_set');
          $table->integer('id_meal_plan');
          $table->date('departure_date');
          //$table->primary(['id_package','id_room_category','id_price_set','id_meal_plan','departure_date']);
          $table->double('gross',8,2);
          $table->double('tax',8,2)->nullable();
          $table->string('soap_client');

          //$table->index(['id_room_category', 'id_price_set','id_meal_plan']);
      });
      DB::statement('ALTER TABLE  `cached_prices`
        ADD PRIMARY KEY (  `id_package` ,  `id_room_category` ,  `id_price_set` ,  `id_meal_plan`,`departure_date`,`soap_client` ) ;');

        DB::statement('CREATE INDEX  `id_room_category`
        ON cached_prices ( `id_room_category`);');
        DB::statement('CREATE INDEX  `id_price_set`
        ON cached_prices ( `id_price_set`);');
        DB::statement('CREATE INDEX  `id_meal_plan`
        ON cached_prices ( `id_meal_plan`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cached_prices');
    }
}
