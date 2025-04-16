<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class CreateDocument extends Component
{
    public $nom;
    public $description;

    // Fonction pour enregistrer le document
    public function save()
    {
        // Validation des champs
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Création du document en base
        Document::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'user_id' => Auth::id(), // On associe l'ID de l'utilisateur connecté
        ]);

        // Message flash pour la réussite
        session()->flash('success', 'Document créé avec succès.');

        // Redirection vers la liste des documents
        return redirect()->route('documents.index');
    }

    // Fonction de rendu de la vue
    public function render()
    {
        return view('livewire.documents.create-document');
    }
}
