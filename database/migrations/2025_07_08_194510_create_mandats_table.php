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
        Schema::create('mandats', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('user_id_from')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id_to')->constrained('users')->cascadeOnDelete();
            $table->foreignId('groupe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('theme_id')->constrained()->cascadeOnDelete();
        
            $table->enum('type', ['delegation', 'procuration']);
            $table->timestamp('fin_at')->nullable();

            $table->unique(['user_id_from', 'groupe_id', 'theme_id'], 'mandat_unique_triplet');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mandats');
    }
};
