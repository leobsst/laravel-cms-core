<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Leobsst\LaravelCmsCore\Enums\LogStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('message');
            $table->bigInteger('creator_id')->unsigned()->index()->nullable();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('reference_table')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('data')->nullable();
            $table->string('status')->default(LogStatus::SUCCESS->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
