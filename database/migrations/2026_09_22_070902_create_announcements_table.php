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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('placement', 20)->index();
            $table->string('title', 200)->nullable();
            $table->text('message');
            $table->string('link_label', 100)->nullable();
            $table->string('link_url', 255)->nullable();
            $table->string('image_path')->nullable();
            $table->string('frequency', 20)->default('always');
            $table->unsignedSmallInteger('frequency_days')->nullable();
            $table->boolean('is_dismissible')->default(true);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
