<?php

namespace App\Livewire\Sessions;

use App\Models\User;
use App\Models\Session;
use Livewire\Component;
use Illuminate\Support\Carbon;

class Create extends Component
{
    public $lieu;
    public $nom;
    public $president;
    public $ouverture;
    public $fermeture;
    public $users = [];

    public function mount()
    {
        // Récupère tous les utilisateurs, trie par nom
        $this->users = User::orderBy('name')->get();
    }

    // Fonction pour enregistrer la session
    public function save()
    {
        // Validation des champs
        $this->validate([
            'lieu' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'president' => 'integer|exists:users,id',
            'ouverture' => 'required|date|after:now',
            'fermeture' => 'nullable|date|after:ouverture',
        ]);

        // Création de la session en base
        Session::create([
            'lieu' => $this->lieu,
            'nom' => $this->nom,
            'user_id' => $this->president,
            'ouverture' => Carbon::parse($this->ouverture, 'Europe/Paris'),
            $this->fermeture ? Carbon::parse($this->fermeture, 'Europe/Paris') : null,
        ]);

        // Message flash pour la réussite
        session()->flash('success', 'Session créée avec succès.');

        // Redirection vers la liste des documents
        return redirect()->route('documents.create');
    }

    public function render()
    {
        return view('livewire.sessions.create');
    }
}
