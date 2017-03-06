<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('package_categories', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_package');
          $table->integer('id_category');
          $table->string('soap_client');//no default
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages_categories');
    }
}
