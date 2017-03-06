<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('file_infos', function (Blueprint $table) {
          $table->integer('id');
          $table->integer('id_hotel')->nullable();
          $table->integer('id_room_category')->nullable();
          $table->integer('id_geography')->nullable();
          $table->string('soap_client');
          $table->string('name')->nullable();
          $table->string('mime_type')->nullable();
          //$table->primary(['id','soap_client']);

          //$table->index(['id_hotel', 'id_room_category','id_geography']);
      });
      DB::statement('ALTER TABLE  `file_infos`
        ADD PRIMARY KEY (  `id` ,  `soap_client` ) ;');

        DB::statement('CREATE INDEX  `id_hotel`
        ON file_infos ( `id_hotel`);');
        DB::statement('CREATE INDEX  `id_room_category`
        ON file_infos ( `id_room_category`);');
        DB::statement('CREATE INDEX  `id_geography`
        ON file_infos ( `id_geography`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('file_infos');
    }
}
