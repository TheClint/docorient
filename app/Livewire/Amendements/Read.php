<?php

namespace App\Livewire\Amendements;

use App\Models\Statut;
use App\Models\Session;
use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;
use App\Services\VoteService;
use Illuminate\Support\Facades\Auth;

class Read extends Component
{
    public $amendement;
    public $document;
    public bool $president = false;
    public $mode = "consultation";

    protected $listeners = ['reloadRead' => '$refresh'];

    public function mount(Amendement $amendement)
    {
        // Récupère l'amendement
        $this->amendement = $amendement;

        // récupération de l'id du document associé au premier segment de l'amendement
        $documentId = $this->amendement->propositions[0]->document->id;

        $this->document = Document::find($documentId);

        $this->president = $this->estSessionPresident();
    }

    // renvoi de l'amendement vers le composant president
    public function mettreAuVote(){
        if($this->estSessionPresident())
            $this->dispatch('lancerVote');
        return route('sessions.president', ['sessionId' => $this->document->session_id]);
    }

    // supprime l'amendement en cours choisi pour revenir en arrière
    public function retour(){
        if($this->estSessionPresident())
            $this->dispatch('amendementChoisi', null);
    }

    // en cas de session et de présidence
    public function estSessionPresident(){
        $estSessionPresident = false;
        if($this->document->session_id){
            $session = Session::find($this->document->session_id);
            if(
            now()->greaterThanOrEqualTo($session->ouverture) // si la session est ouverte avant maintenant
            && ($session->user_id == Auth::id()) // si le président de la session est l'utilisateur authentifié
            && ($this->amendement->vote_fermeture == null) // si l'amendement n'a pas déjà été mis au vote
            && (Statut::find($this->amendement->statut_id)->libelle === "non voté") // si le vote n'est pas déjà voté
            ){
                // si il existe une date de session de fermeture, et que celle-ci est plus tard que maintenant
                if($session->fermeture){
                    if(now()->lessThanOrEqualTo($session->fermeture))
                        $estSessionPresident = true;
                }else
                    $estSessionPresident = true;
            }
        }    
        return $estSessionPresident;   
    }

    public function render()
    {
        return view('livewire.amendements.read');
    }

    public function test($amendementId){
        VoteService::comptabiliserVoteAmendement($this->amendement);
    }
}
