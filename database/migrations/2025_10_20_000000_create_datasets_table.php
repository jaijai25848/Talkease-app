<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('language', 16)->default('en');
            $table->enum('type', ['word','sentence'])->default('word');
            $table->string('level')->default('Easy'); // Easy, Medium, Hard, Insane
            $table->boolean('is_public')->default(true);
            $table->unsignedInteger('item_count')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('dataset_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['word','sentence'])->default('word');
            $table->string('text');
            $table->string('category')->nullable();
            $table->string('difficulty')->default('Easy');
            $table->string('ipa')->nullable();
            $table->json('metadata')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('tts_voice')->nullable();
            $table->timestamps();

            $table->index(['dataset_id','difficulty']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dataset_items');
        Schema::dropIfExists('datasets');
    }
};
