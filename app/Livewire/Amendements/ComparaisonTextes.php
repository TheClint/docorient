<?php

namespace App\Livewire\Amendements;

use App\Models\Segment;
use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;
use App\Services\TexteService;

class ComparaisonTextes extends Component
{
    public $amendement;
    public $texteOriginal = "";
    public $formattedTextOriginal = "";
    public $formattedTextAmende = "";
    public $motsTextOriginal = [];
    public $motsTextAmende = [];
    public $positionsDiffTextOriginal = [];
    public $positionsDiffTextAmende = [];
    public $segmentsAvant;
    public $segmentsApres;
    public $document;

    public function mount($amendement)
    {
        // Récupère l'amendement
        $this->amendement = Amendement::with('propositions')->findOrFail($amendement->id);

        // récupération de l'id du document associé au premier segment de l'amendement
        $documentId = $this->amendement->propositions[0]->document->id;

        $this->document = Document::find($documentId);

        $this->segmentsAvant = Segment::where('document_id', $documentId)
        ->where('id', '<', $this->amendement->propositions[0]->id)
        ->orderBy('id')
        ->get();
        $this->segmentsApres = Segment::where('document_id', $documentId)
        ->where('id', '>', $this->amendement->propositions[count($this->amendement->propositions)-1]->id)
        ->orderBy('id')
        ->get();

        foreach ($this->amendement->propositions as $proposition) {
            $this->texteOriginal .= $proposition->texte;
        }

        $this->motsTextOriginal = TexteService::splitText($this->texteOriginal);
        $this->motsTextAmende = TexteService::splitText($this->amendement->texte);

        // Appel à LCS pour obtenir les positions de différences
        $resultat = TexteService::LCS($this->motsTextOriginal, $this->motsTextAmende);

        $this->positionsDiffTextOriginal = $resultat['positionDiffTexte1'];
        $this->positionsDiffTextAmende  = $resultat['positionDiffTexte2'];
       
        // Génère les textes avec les différences surlignées
        $this->formattedTextOriginal = TexteService::highlightDifferences($this->motsTextOriginal, $this->positionsDiffTextOriginal);
        $this->formattedTextAmende = TexteService::highlightDifferences($this->motsTextAmende, $this->positionsDiffTextAmende);
    }

    public function render()
    {
        return view('livewire.amendements.comparaison-textes');
    }
}
