<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalonImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salon_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('image')->nullable();

            $table->unsignedBigInteger('salon_id');
            $table->foreign('salon_id')->references('id')->on('salons');

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
        Schema::dropIfExists('salon_images');
    }
}
