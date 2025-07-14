<?php

namespace App\Livewire\Fusion;

use Livewire\Component;
use App\Models\Segment;
use App\Models\Amendement;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $documentId;
    public array $groupesConnexes = [];
    public bool $president = false;
    public bool $commissaire = false;
    public string $mode = 'consultation';

    public function mount(Document $documentId): void
    {
        $this->documentId = $documentId->id;

        $document = Document::with('session')->findOrFail($this->documentId);
        $this->mode = $document->session ? 'session' : 'consultation';
        $this->president = $document->session && $document->session->user_id === Auth::id();
        $this->commissaire = $document->session && in_array(Auth::id(), $document->session->commissaires ?? []);

        $this->groupesConnexes = $this->calculerGroupesConnexes($document);
    }

    protected function calculerGroupesConnexes(Document $document): array
    {
        $segmentsConflictuels = Segment::where('document_id', $document->id)
            ->whereHas('modifications', function ($query) {
                $query->select('segment_id')
                    ->groupBy('segment_id')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->with('propositions')
            ->with('modifications')
            ->orderBy('id')
            ->get();

        $segmentsATraiter = collect($segmentsConflictuels);
        $groupesConnexes = [];

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

            $groupe = $groupe->sortBy('id')->values();

            $segmentIds = $groupe->pluck('id')->toArray();

            $amendements = Amendement::whereHas('statut', fn ($q) => $q->where('libelle', 'adopté'))
                ->whereHas('propositions', fn ($q) => $q->whereIn('segments.id', $segmentIds))
                ->with(['propositions', 'statut', 'user'])
                ->get();

            $texteConflit = $groupe->pluck('texte')->implode(' ');
            $tailleMax = mb_strlen($texteConflit);

            $tailleMax = 0;

            foreach ($groupe as $segment) {
                // Récupère la taille maximale d'une modification de ce segment
                $tailleSegment = $segment->modifications
                    ->filter(fn($mod) => !is_null($mod->texte))
                    ->map(fn($mod) => mb_strlen($mod->texte))
                    ->max() ?? 0;

                $tailleMax += $tailleSegment;
            }


            // Comptage des fusions déjà proposées
            $fusionQuery = Amendement::whereHas('propositions', fn ($q) =>
                $q->whereIn('segments.id', $segmentIds)
            );

            if ($this->mode === 'session' && $document->session) {
                $fusionQuery->where('created_at', '>', $document->session->ouverture);
            } else {
                $fusionQuery->where('created_at', '>', $document->vote_fermeture);
            }

            $nbFusions = $fusionQuery->count();

            $groupesConnexes[] = [
                'segment_ids' => $segmentIds,
                'amendements' => $amendements,
                'texte_conflit' => mb_substr($texteConflit, 0, 300),
                'taille_max_conflit' => $tailleMax,
                'fusions_count' => $nbFusions,
            ];
        }

        return $groupesConnexes;
    }

    public function render()
    {
        return view('livewire.fusion.index');
    }
}
