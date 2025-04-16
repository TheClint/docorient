<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\Document;

class EditDocument extends Component
{
    public $documentId;
    public $nom;
    public $description;

    // Chargement des données existantes
    public function mount($document)
    {
        $doc = Document::findOrFail($document);
        $this->documentId = $doc->id;
        $this->nom = $doc->nom;
        $this->description = $doc->description;
    }

    // Sauvegarde des modifications
    public function update()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Document::where('id', $this->documentId)->update([
            'nom' => $this->nom,
            'description' => $this->description,
        ]);

        session()->flash('success', 'Document modifié avec succès.');
        return redirect()->route('documents.index');
    }

    public function render()
    {
        return view('livewire.documents.edit-document');
    }
}

