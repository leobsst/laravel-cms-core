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
        Schema::create('page_themes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('banner')->nullable();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_content')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->foreignId('theme_id')->nullable()->constrained('page_themes')->nullOnDelete();
            $table->longText('content')->nullable();
            $table->longText('draft');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_home')->default(false);
            $table->boolean('is_default')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('banner')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_themes');
        Schema::dropIfExists('pages');
    }
};
