<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('latitude',10,8)->nullable();
            $table->decimal('longitude',11,8)->nullable();
            $table->integer('site_id');
            $table->integer('address_id')->nullable();
            $table->integer('contact_id')->nullable();
            $table->integer('default_room_id')->nullable();
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
        Schema::dropIfExists('buildings');
    }
}
