<?php

namespace App\Livewire\Amendements;

use Carbon\Carbon;
use App\Models\Statut;
use App\Models\Segment;
use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public int $documentId; // correspond à {documentId} dans la route
    public Document $document;
    public $segments;

    public $segmentDebutId = null;
    public $segmentFinId = null;
    public $texte = ''; // Ceci contient le texte complet sélectionné
    public string $texteModifiable = ''; // Le texte à amender
    public $commentaire = '';

    public function mount()
    {
        $this->document = Document::find($this->documentId);

        $this->segments = Segment::where('document_id', $this->documentId)
                                 ->orderBy('id')
                                 ->get();
    }

    public function selectSegment($id)
    {
        if (!$this->segmentDebutId) {
            $this->segmentDebutId = $id;
            $this->segmentFinId = null;
            $this->texteModifiable = '';
        } elseif (!$this->segmentFinId) {
            $this->segmentFinId = $id;

            if ($this->segmentDebutId > $this->segmentFinId) {
                [$this->segmentDebutId, $this->segmentFinId] = [$this->segmentFinId, $this->segmentDebutId];
            }

            // Récupérer les segments sélectionnés entre début et fin
            $selectedSegments = $this->segments
                ->filter(fn($s) => $s->id >= $this->segmentDebutId && $s->id <= $this->segmentFinId)
                ->pluck('texte')
                ->implode('');

            // Maintenant, on met à jour le texte modifiable
            $this->texteModifiable = $selectedSegments;
        } else {
            $this->segmentDebutId = $id;
            $this->segmentFinId = null;
            $this->texteModifiable = '';
        }
    }

    public function save()
    {

        if(Carbon::now()>$this->document->amendement_fermeture || Carbon::now()<$this->document->amendement_ouverture){
            session()->flash('error', 'Amendement hors-delai');
            return;
        }

        if(!isset($this->segmentDebutId))
            session()->flash('warning', 'Vous n\'avez pas selectionné de plage');
        else{

            $this->validate([
                'segmentDebutId' => 'required|exists:segments,id',
                'segmentFinId' => 'required|exists:segments,id',
                'texteModifiable' => 'string',
                'commentaire' => 'nullable|string',
            ]);
            

            // Récupérer les segments sélectionnés entre début et fin
            $selectedSegments = $this->segments
            ->filter(fn($s) => $s->id >= $this->segmentDebutId && $s->id <= $this->segmentFinId)
            ->pluck('texte')
            ->implode('');

            // if()
            // vérification si l'amendement proposé est différent du texte original
            if(strcmp($this->texteModifiable, $selectedSegments)){

                $amendement = Amendement::create([
                    'texte' => $this->texteModifiable,
                    'commentaire' => $this->commentaire,
                    'user_id' => Auth::id(),
                    'statut_id' => Statut::where("libelle", "non voté")->first()->id,
                ]);

                $tableauIdSegmentsAmende = array();
                foreach($this->segments as $segment){
                    if($segment->id >=  $this->segmentDebutId && $segment->id <= $this->segmentFinId)
                        array_push($tableauIdSegmentsAmende, $segment->id);
                }

                $amendement->propositions()->attach($tableauIdSegmentsAmende);

                session()->flash('success', 'Amendement proposé avec succès.');

                redirect()->route('documents.read', ['documentId' => $this->documentId]);
            }else{
                session()->flash('error', 'L\'amendement proposé est identique au texte original');
            }
        } 

        // Reset formulaire
        $this->reset(['segmentDebutId', 'segmentFinId', 'texteModifiable', 'commentaire']);
    }

    public function render()
    {
        return view('livewire.amendements.create');
    }
}
