<?php

namespace App\Livewire\Sessions;

use App\Models\Statut;
use App\Models\Session;
use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;

class President extends Component
{
    public Session $session;
    public $documentEnCours;
    public ?Amendement $amendementEnCours = null;
    protected $listeners = ['documentChoisi', 'amendementChoisi', 'lancerVote', 'passerAmendementSuivant', 'cloreLaSession'];
    

    public function mount($sessionId)
    {
        $this->session = Session::find($sessionId);

        $this->chargerAmendementEnCours();
       
        $this->documentEnCours = ($this->amendementEnCours) ? $this->amendementEnCours->modifications()->with('document')->first()?->document : null;
    }


    public function documentChoisi($documentId)
    {
        // Récupération du document choisi
        $this->documentEnCours = Document::find($documentId);
    }

    public function amendementChoisi($amendementId)
    {
        // Récupération de l'amendement choisi
        $this->session->amendement_id = $amendementId;
        $this->session->save();
        $this->amendementEnCours = null;
        $this->chargerAmendementEnCours();
    }

    public function chargerAmendementEnCours(): void
    {
        if ($this->session->amendement_id) {
            $this->amendementEnCours = Amendement::find($this->session->amendement_id);
        }
    }

    public function lancerVote(): void
    {
        if (!$this->amendementEnCours) return;

        $this->amendementEnCours->vote_fermeture = now()->addSeconds(30);
        $this->amendementEnCours->save();
        $this->dispatch('reloadRead');
    }

    public function passerAmendementSuivant(): void
    {
        $this->amendementEnCours = null;

        // vérification s'il existe encore un amendement à voter dans le document en cours
        if(!Document::where('id', $this->documentEnCours->id)
            ->whereHas('segments.modifications.statut', function ($query) {
                $query->where('libelle', 'non voté');
            })->exists())
            $this->documentEnCours = null;
    }

    public function cloreLaSession()
    {
        $this->session->fermeture = now();
        $this->session->save();

        return redirect()->route('sessions.index');
    }

    public function render()
    {
        return view('livewire.sessions.president');
    }

    public function poll()
    {
        // force l'actualisation de tous les components en attendant une technologie plus complète comme websocket
    }

}
