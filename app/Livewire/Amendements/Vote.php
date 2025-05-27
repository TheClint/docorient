<?php

namespace App\Livewire\Amendements;

use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Vote extends Component
{
    public Amendement $amendement;
    public ?string $voteSelectionne = null;
    public string $etatVote = "ouvert";
    public Document $document;
    public $vote_fermeture;
    public $prochaineEtape;

    public function mount(Amendement $amendement)
    {
        $this->amendement = Amendement::with('modifications')->findOrFail($amendement->id);
        $this->document = Document::find($this->amendement->modifications->first()->document_id);

        // recherche le vote de fermeture s'il existe
        $this->vote_fermeture = $this->document?->vote_fermeture;
        if($this->amendement->vote_fermeture)
            $this->vote_fermeture = $this->amendement->vote_fermeture;

        // Charge le vote existant de l'utilisateur
        $vote = $this->amendement->votes()
            ->withPivot('approbation') // nécessaire ici
            ->where('user_id', Auth::id())
            ->first();
        // s'il existe, récupère sa valeur
        $this->voteSelectionne = $vote?->vote->approbation;

        // Vérifie si l'état du vote
        $this->etatVote = $this->etatVote();

        if($this->etatVote == "attente")
            $this->prochaineEtape = $this->document->amendement_ouverture;
        elseif($this->etatVote == "ouvert")
            $this->prochaineEtape = $this->vote_fermeture;
        else
            $this->prochaineEtape = null;

        $this->prochaineEtape = Carbon::parse($this->prochaineEtape);
    }

    public function etatVote(): string
    {
        $now = now();
        // si les votes de sont pas encore ouvert
        

        if($this->document->amendement_ouverture){
            if($now->lessThanOrEqualTo($this->document->amendement_ouverture)){
                return "attente";
            }
        }
        
        // S'il y a une date de vote de fermeture
        if($this->vote_fermeture){
            if($now->greaterThanOrEqualTo($this->vote_fermeture))
                return "ferme";
        }
        
        // Si aucune condition de fermeture n'est définie, le vote est ouvert
        return "ouvert";

    }

    public function voter(string $choix)
    {
        // Vérifie si l'état du vote au moment du clic
        $this->etatVote = $this->etatVote();

        if ($this->etatVote == "attente"){
            session()->flash('warning', 'Le vote n\'a pas encore commencé.');
            return;
        }
        elseif($this->etatVote == "ferme"){
            session()->flash('warning', 'Le vote est fermé');
            return;
        }

        $userId = Auth::id();

        // Si l'utilisateur a déjà voté, on met à jour son vote
        $this->amendement->votes()->syncWithoutDetaching([
            $userId => ['approbation' => $choix]
        ]);

        $this->voteSelectionne = $choix;
    }

    public function render()
    {
        return view('livewire.amendements.vote');
    }

    public function getProchaineEtapeTimestampProperty()
{
    return $this->prochaineEtape ? \Carbon\Carbon::parse($this->prochaineEtape)->timestamp : null;
}
}
