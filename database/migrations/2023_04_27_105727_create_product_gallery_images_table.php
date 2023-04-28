<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_gallery_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pid')->unsigned();
            $table->foreign('pid')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_gallery_images');
    }
};
