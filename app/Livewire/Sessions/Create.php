<?php

namespace App\Livewire\Sessions;

use App\Models\User;
use App\Models\Groupe;
use App\Models\Session;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $lieu;
    public $nom;
    public $president;
    public $ouverture;
    public $fermeture;
    public $users = [];
    public $groupes;
    public $groupe;

    public function mount()
    {
        $this->groupes = User::find(Auth::id())->groupes;
    }

    public function updatedGroupe($value)
    {
        $this->users = Groupe::find($value)?->membres()->orderBy('name')->get() ?? collect();
    }

    // Fonction pour enregistrer la session
    public function save()
    {
        // Validation des champs
        $this->validate([
            'lieu' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'groupe' => 'integer|required||exists:groupes,id',
            'president' => 'integer|required|exists:users,id',
            'ouverture' => 'required|date|after:now',
            'fermeture' => 'nullable|date|after:ouverture',
        ]);

        // Création de la session en base
        Session::create([
            'lieu' => $this->lieu,
            'nom' => $this->nom,
            'user_id' => $this->president,
            'groupe_id' => $this->groupe,
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
