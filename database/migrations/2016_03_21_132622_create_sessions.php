<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('sessions', function (Blueprint $table) {
          $table->string('id',50);
          $table->longText('payload');
          $table->integer('last_activity',20);
          //$table->primary(['id','payload']);
          //$table->dropPrimary('last_activity');

      });
      //DB::statement('ALTER TABLE  `sessions` , DROP PRIMARY KEY
      //  ADD PRIMARY KEY (  `id` ) ;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sessions');
    }
}
