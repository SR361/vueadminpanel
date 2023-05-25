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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('logo')->nullable();
            $table->string('login_background')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();

            $table->bigInteger('currency_id')->unsigned()->nullable();
            // $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('package_id')->unsigned()->nullable();
            // $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('package_type', ['monthly', 'annual'])->default('monthly');

            $table->string('timezone')->default('Asia/Kolkata');
            $table->string('date_format', 20)->default('d-m-Y');
            $table->string('date_picker_format')->nullable();
            $table->string('time_format', 20)->default('h:i a');
            $table->integer('week_start')->default(1);
            $table->string('locale')->default('en');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('leaves_start_from', ['joining_date', 'year_start'])->default('joining_date');
            $table->enum('active_theme', ['default', 'custom'])->default('default');
            $table->enum('status', ['active', 'inactive','license_expired'])->default('active');
            $table->enum('task_self', ['yes', 'no'])->default('yes');
            $table->bigInteger('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->date('licence_expire_on')->nullable();
            $table->boolean('rounded_theme')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('companies');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('companies');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
