<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Leobsst\LaravelCmsCore\Enums\SettingCategoryEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->longText('value')->nullable();
            $table->longText('default_value')->nullable();
            $table->string('type')->default('string')->comment('string, integer, boolean, json, serialized, date, email, url, textarea, color');
            $table->string('category')->default(SettingCategoryEnum::GENERAL->value);
            $table->boolean('is_default')->default(false);
            $table->boolean('enabled')->default(true);
            $table->boolean('protected')->default(false);
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
