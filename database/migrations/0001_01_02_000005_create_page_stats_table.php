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
        Schema::create('page_stats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id')->unsigned()->index();
            $table->foreign('page_id')
                ->references('id')->on('pages')
                ->onDelete('cascade');
            $table->string('ip')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->integer('ios')->default(0);
            $table->integer('android')->default(0);
            $table->integer('other')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('ios');
            $table->dropColumn('android');
            $table->dropColumn('other');
        });

        Schema::dropIfExists('page_stats');
    }
};
