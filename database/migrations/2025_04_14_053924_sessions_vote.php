<?php

use App\Models\User;
use App\Models\Amendement;
use App\Models\Groupe;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sessions_vote', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nom');
            $table->string('lieu');
            // président
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Groupe::class);
            $table->dateTime('ouverture');
            $table->dateTime('fermeture')->nullable();
            $table->foreignIdFor(Amendement::class)
                  ->nullable()
                  ->nullOnDelete();
            $table->json('commissaires')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions_vote');
    }
};
