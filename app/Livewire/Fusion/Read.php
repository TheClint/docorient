<?php

namespace App\Livewire\Fusion;

use Livewire\Component;
use App\Models\Segment;
use App\Models\Document;
use App\Models\Amendement;
use App\Models\Modification;
use App\Services\TexteService;
use Illuminate\Support\Facades\Auth;

class Read extends Component
{
    public Segment $segment;
    public Document $document;

    public array $groupeSegmentId = [];
    public bool $president = false;
    public string $mode = 'consultation';

    public ?int $indexGauche = null;
    public ?int $indexDroite = null;

    public string $texteHighlightGauche = '';
    public string $texteHighlightDroite = '';

    public string $texteOriginal = '';

    public function mount(int $segmentId): void
    {
        $this->segment = Segment::findOrFail($segmentId);
        $this->document = Document::with('session')->findOrFail($this->segment->document_id);
        $this->mode = $this->document->session ? 'session' : 'consultation';
        $this->president = $this->document->session && $this->document->session->user_id === Auth::id();

        $this->groupeSegmentId = $this->calculerGroupeSegmentsId($this->document);

        $segments = Segment::whereIn('id', $this->groupeSegmentId)->get()->keyBy('id');

        $this->texteOriginal = '';

        foreach ($this->groupeSegmentId as $segmentId) {
            $this->texteOriginal .= $segments[$segmentId]->texte;
        }
    }

    public function getAmendementsProperty()
    {
        $segments = Segment::whereIn('id', $this->groupeSegmentId)->get()->keyBy('id');
        $texte1 = preg_split('/(\s+|\b)/u', $this->texteOriginal, -1, PREG_SPLIT_DELIM_CAPTURE);

        $modifications = Modification::whereIn('segment_id', $this->groupeSegmentId)
            ->with(['segment', 'amendement'])
            ->get()
            ->groupBy('amendement_id');

        $amendements = [];

        foreach ($modifications as $groupModifs) {
            $modifsParSegment = $groupModifs->keyBy('segment_id');
            $texteConcat = '';

            foreach ($this->groupeSegmentId as $segmentId) {
                $texteConcat .= $modifsParSegment[$segmentId]->texte ?? $segments[$segmentId]->texte;
            }

            $amendement = $groupModifs->first()?->amendement;
            if ($amendement) {
                $texte2 = preg_split('/(\s+|\b)/u', $texteConcat, -1, PREG_SPLIT_DELIM_CAPTURE);
                $diffs = TexteService::LCS($texte1, $texte2);

                $amendement->texteReconstruit = $texteConcat;
                $amendement->texteReconstruitHighlight = TexteService::highlightDifferences($texte2, $diffs['positionDiffTexte2']);
                $amendement->texteOriginalHighlight = TexteService::highlightDifferences($texte1, $diffs['positionDiffTexte1']);

                $amendements[] = $amendement;
            }
        }

        return $amendements;
    }

    public function getAmendementsFusionProperty()
    {
        $fusionQuery = Amendement::whereHas('propositions', fn ($q) =>
            $q->whereIn('segments.id', $this->groupeSegmentId)
        );
       
        if ($this->mode === 'session' && $this->document->session) {
            $fusionQuery->where('created_at', '>', $this->document->session->ouverture);
        } else {
            $fusionQuery->where('created_at', '>', $this->document->vote_fermeture);
        }

        return $fusionQuery->get();
    }

    public function comparerTexte(string $cote, int $index): void
    {
        if ($cote === 'gauche') {
            $this->indexGauche = $this->indexGauche === $index ? null : $index;
        } elseif ($cote === 'droite') {
            $this->indexDroite = $this->indexDroite === $index ? null : $index;
        }

        // Réinitialisation systématique
        $this->texteHighlightGauche = '';
        $this->texteHighlightDroite = '';

        // Ne pas comparer tant que les deux côtés ne sont pas sélectionnés
        if ($this->indexGauche === null || $this->indexDroite === null) {
            return;
        }

        $texteGauche = preg_split(
            '/(\s+|\b)/u',
            $this->amendements[$this->indexGauche]->texteReconstruit ?? '',
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        $texteDroite = preg_split(
            '/(\s+|\b)/u',
            $this->amendementsFusion[$this->indexDroite]->texte ?? '',
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        $diffs = TexteService::LCS($texteGauche, $texteDroite);

        $this->texteHighlightGauche = TexteService::highlightDifferences($texteGauche, $diffs['positionDiffTexte1']);
        $this->texteHighlightDroite = TexteService::highlightDifferences($texteDroite, $diffs['positionDiffTexte2']);
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

        foreach ($groupes as $groupe) {
            if ($groupe->pluck('id')->contains($this->segment->id)) {
                return $groupe->pluck('id')->toArray();
            }
        }

        return [];
    }

    public function render()
    {
        return view('livewire.fusion.read');
    }
}
