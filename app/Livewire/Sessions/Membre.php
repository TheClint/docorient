<?php

namespace App\Livewire\Sessions;

use App\Models\Statut;
use App\Models\Session;
use Livewire\Component;
use App\Models\Amendement;

class Membre extends Component
{
    public Session $session;
    public $documentEnCours;
    public ?Amendement $amendementEnCours = null;
    public $ancienEtat = false;

    public function mount($sessionId)
    {
        $this->session = Session::find($sessionId);

        $this->chargerAmendementEnCours();
       
        $this->documentEnCours = ($this->amendementEnCours) ? $this->amendementEnCours->propositions()->with('document')->first()?->document : null;
    }

    public function chargerAmendementEnCours(): void
    {
        if ($this->session->amendement_id) {
            $this->amendementEnCours = Amendement::find($this->session->amendement_id);

            if ($this->amendementEnCours) {

                if ( $this->amendementEnCours->statut_id === Statut::whereLibelle('non voté')->first()?->id) {
                    $this->amendementEnCours->refresh();
                }
            }
        }
    }

    public function poll()
    {
        // force l'actualisation de tous les components en attendant une technologie plus complète comme websocket
        $this->chargerAmendementEnCours();
       
        $this->documentEnCours = ($this->amendementEnCours) ? $this->amendementEnCours->propositions()->with('document')->first()?->document : null;

        if($this->amendementEnCours !== null){
            if($this->ancienEtat == false && $this->amendementEnCours->statut->libelle != "non voté"){
                $this->dispatch('amendementVote', ['id' => $this->amendementEnCours->id]);
                $this->ancienEtat = true;
            }

            if($this->ancienEtat === true && $this->amendementEnCours->statut->libelle === "non voté")
                $this->ancienEtat = false;
        }

        $this->render();
    }

    public function render()
    {
        return view('livewire.sessions.membre');
    }
}
