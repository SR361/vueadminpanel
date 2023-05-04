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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('site_logo')->nullable();
            $table->text('theme')->nullable();
            $table->text('footer')->nullable();
            $table->text('header_setting')->nullable();
            $table->text('sidebar_option')->nullable();
            $table->text('navbar_variants')->nullable();
            $table->text('accent_color_variants')->nullable();
            $table->text('dark_sidebar_variants')->nullable();
            $table->text('light_sidebar_variants')->nullable();
            $table->text('brand_logo_variants')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
