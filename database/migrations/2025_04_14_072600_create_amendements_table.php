<?php

use App\Models\Amendement;
use App\Models\User;
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
        Schema::create('amendements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text("texte");
            $table->text("commentaire")->nullable();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Amendement::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amendements');
    }
};
