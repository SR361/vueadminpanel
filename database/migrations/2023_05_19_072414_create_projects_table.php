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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');


            $table->string('short_code');
            $table->string('project_name');
            $table->mediumText('project_summary')->nullable();

            $table->bigInteger('project_admin')->unsigned()->nullable();
            $table->foreign('project_admin')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->date('start_date');
            $table->date('deadline');
            $table->longText('notes')->nullable();

            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('project_categories')->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->mediumText('feedback')->nullable();
            $table->enum('manual_timelog', ['enable', 'disable'])->default('disable');
            $table->enum('client_view_task', ['enable', 'disable'])->default('disable');
            $table->enum('allow_client_notification', ['enable', 'disable'])->default('disable');
            $table->tinyInteger('completion_percent');
            $table->enum('calculate_task_progress', ['true', 'false'])->default('true');
            $table->double('project_budget', 20, 2)->nullable();

            $table->bigInteger('currency_id')->unsigned()->nullable();
            // $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');

            $table->float('hours_allocated')->nullable();
            $table->enum('status', ['not started', 'in progress', 'on hold', 'canceled', 'finished'])->default('in progress');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
