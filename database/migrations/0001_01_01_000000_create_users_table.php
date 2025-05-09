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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->longText('uuid')->nullable()->unique();
            $table->string('name');
            $table->string( 'first_name')->nullable();
            $table->string( 'username')->nullable();
            $table->boolean('username_visible')->default(false);
            $table->string('email')->unique();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->longText('two_fa_secret')->nullable();
            $table->boolean('two_fa_enabled')->default(false);
            $table->string('avatar')->nullable();
            $table->boolean('avatar_gravatar')->default(false);
            $table->longText('bio')->nullable();
            $table->longText('facebok')->nullable();
            $table->longText('twitter')->nullable();
            $table->longText('instagram')->nullable();
            $table->longText('tiktok')->nullable();
            $table->longText('youtube')->nullable();
            $table->longText('pinterest')->nullable();
            $table->longText('linkedin')->nullable();
            $table->longText('github')->nullable();
            $table->longText('website')->nullable();
            $table->longText('extra_data')->nullable()->comment('serialized array');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('user_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
