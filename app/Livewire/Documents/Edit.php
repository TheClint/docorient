<?php

namespace App\Livewire\Documents;

use DateTime;
use Livewire\Component;
use App\Models\Document;
use Illuminate\Support\Carbon;

class Edit extends Component
{
    public $documentId;
    public $nom;
    public $description;
    public $amendement_ouverture;
    public $vote_fermeture;

    // Chargement des données existantes
    public function mount($document)
    {
        $doc = Document::findOrFail($document);
        $this->documentId = $doc->id;
        $this->nom = $doc->nom;
        $this->description = $doc->description;
        $this->amendement_ouverture = (new DateTime($doc->amendement_ouverture))->format('Y-m-d\TH:i');
        $this->vote_fermeture = (new DateTime($doc->amendement_ouverture))->format('Y-m-d\TH:i');
    }

    // Sauvegarde des modifications
    public function update()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amendement_ouverture' => 'nullable|date',
            'vote_fermeture' => 'nullable|date',
        ]);

        Document::where('id', $this->documentId)->update([
            'nom' => $this->nom,
            'description' => $this->description,
            'amendement_ouverture' => Carbon::parse($this->amendement_ouverture, 'Europe/Paris')->setTimezone('UTC'),
            'vote_fermeture' => $this->vote_fermeture ? Carbon::parse($this->vote_fermeture, 'Europe/Paris')->setTimezone('UTC') : null,   
        ]);

        session()->flash('success', 'Document modifié avec succès.');
        return redirect()->route('documents.index');
    }

    public function render()
    {
        return view('livewire.documents.edit');
    }
}

