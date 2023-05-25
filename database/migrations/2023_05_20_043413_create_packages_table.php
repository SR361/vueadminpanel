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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');

            $table->string('name', 255);
            $table->string('description', 1000)->nullable();
            $table->unsignedInteger('max_storage_size');
            $table->unsignedInteger('max_file_size')->default(0);
            $table->unsignedDecimal('annual_price')->default(0);
            $table->unsignedDecimal('monthly_price')->default(0);
            $table->unsignedTinyInteger('billing_cycle')->default(0);
            $table->integer('max_employees')->unsigned()->default(0);
            $table->string('sort');
            $table->string('module_in_package', 1000);
            $table->string('stripe_annual_plan_id', 255);
            $table->string('stripe_monthly_plan_id', 255);
            $table->enum('default', ['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
