<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStylePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('description')->nullable();
            $table->text('media');
            $table->string('tags')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('style_name')->nullable();
            $table->string('color')->nullable();
            $table->unsignedBigInteger('stylist_id')->nullable(); 
            $table->foreign('stylist_id')->references('id')->on('stylists'); 
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
        Schema::dropIfExists('style_posts');
    }
}
