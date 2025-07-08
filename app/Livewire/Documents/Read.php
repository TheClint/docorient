<?php

namespace App\Livewire\Documents;

use App\Models\Segment;
use Livewire\Component;
use App\Models\Document;
use App\Services\TexteService;

class Read extends Component
{
    public $document;
    public $texte = ''; // Texte fusionné pour affichage
    public $estAmendable = false;
    public $fusionOuverte = false;
    public $estFinalise = false;
    public $texteFinalise = '';
    public $formattedTexte;
    public $formattedTexteFinalise;
    public $comparerActive = false;

    public function mount(Document $documentId)
    {
        // Récupère le document et ses segments associés
        $this->document = Document::with('segments')->findOrFail($documentId->id);

        // Fusionne les segments en un seul texte fluide
        $this->texte = $this->document->segments
            ->sortBy('id')
            ->pluck('texte')
            ->implode('');

        // A faire : ajouter une condition sur un document déjà voté
        $now = now();
        if($this->document->amendement_ouverture < $now && $this->document->amendement_fermeture > $now)
            $this->estAmendable = true;
        if($this->document->vote_fermeture !== null && $this->document->vote_fermeture < $now)
            $this->fusionOuverte = true;
        if($this->document->session_id !== null && $this->document->session->ouverture < $now)
            $this->fusionOuverte = true;

        // Verification si document complet
        if($this->fusionOuverte){
            // verification s'il n'existe plus de segment avec des modifications conflictuelles
            $this->estFinalise = Segment::where('document_id', $this->document->id)
            ->whereHas('modifications', function ($query) {
                $query->select('segment_id')
                    ->groupBy('segment_id')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->with('propositions')
            ->with('modifications')
            ->orderBy('id')
            ->get()->isEmpty();
        }

        if($this->estFinalise){
            foreach($this->document->segments as $segment)
                $this->texteFinalise .= $segment->modifications->isEmpty() ? $segment->texte : $segment->modifications->first->texte->texte;
            
            $motsTextOriginal = TexteService::splitText($this->texteFinalise);
            $motsTextAmende = TexteService::splitText($this->texte);
    
            // Appel à LCS pour obtenir les positions de différences
            $resultat = TexteService::LCS($motsTextOriginal, $motsTextAmende);
    
            $positionsDiffTextOriginal = $resultat['positionDiffTexte1'];
            $positionsDiffTextAmende  = $resultat['positionDiffTexte2'];
            
            // Génère les textes avec les différences surlignées
            $this->formattedTexteFinalise = TexteService::highlightDifferences($motsTextOriginal, $positionsDiffTextOriginal);
            $this->formattedTexte = TexteService::highlightDifferences($motsTextAmende, $positionsDiffTextAmende);
        }
    }

    public function comparer()
    {
        $this->comparerActive = !$this->comparerActive;
    }

    public function render()
    {
        return view('livewire.documents.read');
    }
}

