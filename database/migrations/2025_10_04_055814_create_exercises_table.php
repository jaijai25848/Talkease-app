<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['easy','medium','hard','insane'])->index();
            $table->enum('category', ['word','sentence'])->index();
            // Provide multiple possible text fields so controller fallbacks work
            $table->string('word')->nullable();
            $table->text('text')->nullable();
            $table->text('content')->nullable();
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
