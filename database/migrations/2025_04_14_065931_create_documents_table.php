<?php

use App\Models\User;
use App\Models\Theme;
use App\Models\Session;
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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Theme::class);
            $table->timestamp('amendement_ouverture')->nullable();
            $table->timestamp('amendement_fermeture')->nullable();
            $table->timestamp('vote_fermeture')->nullable();
            $table->foreignIdFor(Session::class)
                  ->nullable()
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
