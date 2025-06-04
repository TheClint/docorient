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

    public function mount($sessionId)
    {
        $this->session = Session::find($sessionId);

        $this->chargerAmendementEnCours();
       
        $this->documentEnCours = ($this->amendementEnCours) ? $this->amendementEnCours->modifications()->with('document')->first()?->document : null;
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
       
        $this->documentEnCours = ($this->amendementEnCours) ? $this->amendementEnCours->modifications()->with('document')->first()?->document : null;

        $this->render();
    }

    public function render()
    {
        return view('livewire.sessions.membre');
    }
}
