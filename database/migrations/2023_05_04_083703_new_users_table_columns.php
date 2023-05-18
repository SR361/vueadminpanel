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
        Schema::table('users', function (Blueprint $table) {
            $table->text('designation')->nullable()->after('email');
            $table->text('education')->nullable()->after('designation');
            $table->text('location')->nullable()->after('education');
            $table->text('skills')->nullable()->after('location');
            $table->text('profile_photo')->nullable()->after('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('designation');
            $table->dropColumn('education');
            $table->dropColumn('location');
            $table->dropColumn('skills');
            $table->dropColumn('profile_photo');
        });
    }
};
