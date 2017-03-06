<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MealPlansPackageSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('meal_plans_package_search', function (Blueprint $table) {
          $table->increments('id')->unsigned();
          $table->integer('id_package_search');
          $table->string('name');
          $table->string('code')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meal_plans_package_search');
    }
}
