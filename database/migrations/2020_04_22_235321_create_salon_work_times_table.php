<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalonWorkTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salon_work_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('salon_id')->nullable(); 
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
        Schema::dropIfExists('salon_work_times');
    }
}
