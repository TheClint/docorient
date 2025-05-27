<?php

namespace App\Livewire\Sessions;

use App\Models\Statut;
use App\Models\Session;
use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;
use App\Services\VoteService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class President extends Component
{
    public Session $session;
    public $documentEnCours;
    public ?Amendement $amendementEnCours = null;
    public bool $voteTermine = false;
    public bool $voteEnCours = false;
    public ?string $resultatVote = null;
    protected $listeners = ['documentChoisi', 'amendementChoisi'];
    

    public function mount($sessionId)
    {
        
        $this->session = Session::find($sessionId);

        $this->chargerAmendementEnCours();
    }


    public function documentChoisi($documentId)
    {
        // Récupération du document choisi
        $this->documentEnCours = $documentId;
    }

    public function amendementChoisi($amendementId)
    {
        // Récupération de l'amendement choisi
        $this->session->amendement_id = $amendementId;
        $this->session->save();
        $this->chargerAmendementEnCours();
    }

    public function chargerAmendementEnCours(): void
    {
        if ($this->session->amendement_id) {
            $this->amendementEnCours = Amendement::find($this->session->amendement_id);

            if ($this->amendementEnCours) {
                $this->voteEnCours = $this->amendementEnCours->vote_fermeture && now()->lt($this->amendementEnCours->vote_fermeture);
                $this->voteTermine = $this->amendementEnCours->vote_fermeture && now()->gt($this->amendementEnCours->vote_fermeture);

                if ($this->voteTermine && $this->amendementEnCours->statut_id === Statut::whereLibelle('non voté')->first()?->id) {
                    //VoteService::comptabiliserVoteAmendement($this->amendementEnCours);
                    $this->amendementEnCours->refresh();
                    $this->resultatVote = $this->amendementEnCours->statut->libelle;
                }
            }
        } else {
            // Si aucun amendement en cours, on charge le prochain
            //dd("suite");
            //$this->chargerProchainAmendement();
        }
    }

    public function lancerVote(): void
    {
        if (!$this->amendementEnCours) return;

        $this->amendementEnCours->vote_fermeture = now()->addSeconds(30);
        $this->amendementEnCours->save();
        $this->voteEnCours = true;
    }
/*
    public function passerAmendementSuivant(): void
    {
        $this->resultatVote = null;

        // Marque l'amendement actuel comme terminé (déjà fait si comptabilisé)
        $this->chargerProchainAmendement();
    }

    public function chargerProchainAmendement(): void
    {
        $prochain = Amendement::whereHas('modifications', function ($query) {
                $query->whereIn('segments.id', $this->document->segments->pluck('id'));
            })
            ->where('statut_id', Statut::whereLibelle('non voté')->first()?->id)
            ->orderBy('id')
            ->first();

        if ($prochain) {
            $this->document->update(['amendement_en_cours_id' => $prochain->id]);
            $this->amendementEnCours = $prochain;
            $this->voteEnCours = false;
            $this->voteTermine = false;
        } else {
            // Fin de session : plus d'amendement
            $this->amendementEnCours = null;
            $this->document->update(['amendement_en_cours_id' => null]);
        }
    }*/

    public function render()
    {
        return view('livewire.sessions.president');
    }

    public function poll()
    {
       // $this->chargerAmendementEnCours();
    }
}
