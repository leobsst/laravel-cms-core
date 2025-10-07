<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Leobsst\LaravelCmsCore\Enums\Features\Pages\PageGalleryAlignment;
use Leobsst\LaravelCmsCore\Enums\Features\Pages\PageGalleryOrientation;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->string('identifier');
            $table->string('orientation')->default(PageGalleryOrientation::HORIZONTAL->value);
            $table->string('alignment')->default(PageGalleryAlignment::LEFT->value);
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_galleries');
    }
};
