<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Leobsst\LaravelCmsCore\Enums\FieldTypeEnum;

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
            $table->boolean('no_content')->default(false);
            $table->longText('content')->nullable();
            $table->longText('draft')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_home')->default(false);
            $table->boolean('is_default')->default(false);
            $table->boolean('no_footer')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('banner')->nullable();
            $table->integer('ios')->default(0);
            $table->integer('android')->default(0);
            $table->integer('other')->default(0);
            $table->timestamps();
        });

        Schema::create('page_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->string('key');
            $table->string('name');
            $table->text('value')->nullable();
            $table->text('default_value')->nullable();
            $table->string('type')->default(FieldTypeEnum::STRING->value);
            $table->timestamps();
        });

        Schema::create('pages_seo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('robots')->default('index, follow');
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            $table->string('og_locale')->default('fr_FR');
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_image')->nullable();
            $table->timestamps();
        });

        Schema::create('page_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->string('ip')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_stats');
        Schema::dropIfExists('pages_seo');
        Schema::dropIfExists('page_options');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('page_themes');
    }
};
