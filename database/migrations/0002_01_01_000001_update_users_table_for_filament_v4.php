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
            $table->text('app_authentication_secret')->nullable()->after('password');
            $table->text('app_authentication_recovery_codes')->nullable()->after('app_authentication_secret');
            $table->boolean('has_email_authentication')->default(false)->after('app_authentication_recovery_codes');
        });

        // Update extra_data column from longText to json and remove two_fa columns
        Schema::table('users', function (Blueprint $table) {
            $table->json('extra_data')->nullable()->change();
            $table->dropColumn(['two_fa_secret', 'two_fa_enabled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['app_authentication_secret', 'app_authentication_recovery_codes', 'has_email_authentication']);
            $table->longText('two_fa_secret')->nullable()->after('remember_token');
            $table->boolean('two_fa_enabled')->default(false)->after('two_fa_secret');
            $table->longText('extra_data')->nullable()->change();
        });
    }
};
