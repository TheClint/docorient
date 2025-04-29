<?php

use App\Models\User;
use App\Models\Statut;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amendements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('texte');              // Texte proposé
            $table->text('commentaire')->nullable(); // Optionnel : justification

            $table->foreignIdFor(User::class);  // Auteur de l'amendement
            $table->foreignIdFor(Statut::class);  // statut de l'amendement
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amendements');
    }
};
