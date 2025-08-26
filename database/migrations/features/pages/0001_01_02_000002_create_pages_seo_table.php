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
        Schema::create('pages_seo', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id')->unsigned()->index();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages_seo');
    }
};
