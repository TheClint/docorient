<?php

use App\Models\User;
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
        Schema::create('groupes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("nom");
            $table->foreignId('primus_inter_pares')->nullable()->constrained('users');
            // Delai d'ouverture à la suite du vote pour fusionner les amendements conflictuels
            $table->integer('delai_fusion')->default(72);
            // Delai suite à la fin du delai de fusion pour voter les amendements de fusion
            $table->integer('vote_fusion')->default(96);
            // taux minimum d'acception d'un vote
            $table->integer('taux_majorite')->default(50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupes');
    }
};
