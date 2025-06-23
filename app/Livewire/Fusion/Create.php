<?php

namespace App\Livewire\Fusion;

use Carbon\Carbon;
use App\Models\Statut;
use App\Models\Segment;
use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;
use App\Models\Modification;
use App\Services\TexteService;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public Segment $segment;
    public $amendements = [];
    public $groupeSegmentId = [];
    public bool $president = false;
    public string $mode = 'consultation';
    public Document $document;
    public array $textesGroupes = [];
    public string $texteOriginal = '';
    public string $texteModifiable = '';
    public string $commentaire = '';

    public function mount(int $segmentId): void
    {
        $this->segment = Segment::findOrFail($segmentId);

        $this->document = Document::with('session')->findOrFail($this->segment->document_id);
        $this->mode = $this->document->session ? 'session' : 'consultation';
        $this->president = $this->document->session && $this->document->session->user_id === Auth::id();

        $this->groupeSegmentId = collect($this->calculerGroupeSegmentsId($this->document));

        $segments = Segment::whereIn('id', $this->groupeSegmentId)->get()->keyBy('id');

        $this->texteOriginal = '';

        foreach ($this->groupeSegmentId as $segmentId) {
            $this->texteOriginal .= $segments[$segmentId]->texte;
        }

        $texte1 = preg_split('/(\s+|\b)/u', $this->texteOriginal, -1, PREG_SPLIT_DELIM_CAPTURE);

        $modifications = Modification::whereIn('segment_id', $this->groupeSegmentId)
            ->with(['segment', 'amendement'])
            ->get()
            ->groupBy('amendement_id');

        foreach ($modifications as $groupModifs) {
            // On indexe les modifications par segment_id
            $modifsParSegment = $groupModifs->keyBy('segment_id');

            $texteConcat = '';

            foreach ($this->groupeSegmentId as $segmentId) {
                if ($modifsParSegment->has($segmentId)) {
                    $texteConcat .= $modifsParSegment[$segmentId]->texte;
                } else {
                    $texteConcat .= $segments[$segmentId]->texte;
                }
            }
             $amendement = $groupModifs->first()?->amendement;
             if ($amendement) {
                $amendement->texteReconstruit = $texteConcat;

                $texte2 = preg_split('/(\s+|\b)/u', $amendement->texteReconstruit, -1, PREG_SPLIT_DELIM_CAPTURE);
                $diffs = TexteService::LCS($texte1, $texte2); 
                $amendement->texteOriginalHighlight = TexteService::highlightDifferences($texte1, $diffs['positionDiffTexte1']);
                $amendement->texteReconstruitHighlight = TexteService::highlightDifferences($texte2, $diffs['positionDiffTexte2']);
                $this->amendements[] = $amendement;
            }
        }
        
        // Valeur par défaut du champ texte
        $this->texteModifiable = $this->texteOriginal;
    }

    protected function calculerGroupeSegmentsId(Document $document): array
    {
        $segmentsConflictuels = Segment::where('document_id', $document->id)
            ->whereHas('modifications', function ($query) {
                $query->select('segment_id')
                    ->groupBy('segment_id')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->orderBy('id')
            ->get();

        $segmentsATraiter = collect($segmentsConflictuels);

        $groupes = [];

        while ($segmentsATraiter->isNotEmpty()) {
            $groupe = collect();
            $pile = collect([$segmentsATraiter->shift()]);

            while ($pile->isNotEmpty()) {
                $segmentCourant = $pile->pop();

                if ($groupe->contains(fn($s) => $s->id === $segmentCourant->id)) {
                    continue;
                }

                $groupe->push($segmentCourant);

                $voisins = $segmentsATraiter->filter(fn($s) =>
                    abs($s->id - $segmentCourant->id) === 1
                );

                foreach ($voisins as $voisin) {
                    $pile->push($voisin);
                    $segmentsATraiter = $segmentsATraiter->reject(fn($s) => $s->id === $voisin->id);
                }
            }
            
            $groupes[] = $groupe->sortBy('id')->values();
        }

        $groupeChoisi = [];

        foreach($groupes as $groupe){
            if($groupe->pluck('id')->contains($this->segment->id))
                $groupeChoisi = $groupe->pluck('id')->toArray();
        }
        
        return $groupeChoisi;
    }

    public function proposerAmendement()
    {
        $amendement = Amendement::create([
            'texte' => $this->texteModifiable,
            'commentaire' => $this->commentaire,
            'user_id' => Auth::id(),
            'statut_id' => Statut::where("libelle", "non voté")->first()->id,
            'vote_fermeture' => $this->document->vote_fermeture !== null ? Carbon::parse($this->document->vote_fermeture)->addDays(7) : null,
        ]);

        $amendement->propositions()->attach($this->groupeSegmentId);

        session()->flash('success', 'Amendement de fusion proposé avec succès.');

        redirect()->route('fusion.index', ['documentId' => $this->document->id]);
    }

    public function render()
    {
        return view('livewire.fusion.create');
    }
}
