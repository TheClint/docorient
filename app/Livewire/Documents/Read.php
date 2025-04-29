<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\Document;

class Read extends Component
{
    public $document;
    public $texte = ''; // Texte fusionné pour affichage

    public function mount($document)
    {
        // Récupère le document et ses segments associés
        $this->document = Document::with('segments')->findOrFail($document);

        // Fusionne les segments en un seul texte fluide
        $this->texte = $this->document->segments
            ->sortBy('id')
            ->pluck('texte')
            ->implode('');
    }

    public function render()
    {
        return view('livewire.documents.read');
    }
}

