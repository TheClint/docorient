<?php

namespace App\Livewire\Sessions;

use App\Models\Session;
use Livewire\Component;
use App\Models\Document;

class ChoixDocument extends Component
{

    public $session;
    public $documents;

    public function mount($sessionId)
    {
        $this->session = Session::find($sessionId);
        $this->documents = Document::where('session_id', $sessionId)
                                ->whereHas('segments.propositions.statut', function ($query) {
                                $query->where('libelle', 'non voté'); // adapte le champ ici si besoin
                                })->get();
    }

    public function choixDocumentEnCours($documentId){
        $this->dispatch('documentChoisi', $documentId);
    }

    public function cloreLaSession(){
        $this->dispatch('cloreLaSession');
    }

    public function render()
    {
        return view('livewire.sessions.choix-document');
    }
}
