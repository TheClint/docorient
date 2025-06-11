<?php

namespace App\Livewire\Documents;


use App\Models\Segment;
use App\Models\Session;
use Livewire\Component;
use App\Models\Document;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $nom;
    public $description;
    public $contenu;
    public $amendement_ouverture;
    public $vote_fermeture;
    public bool $automatique = false;
    public $sessions = [];
    public $session;

    public function mount()
    {
        // Récupère toutes les sessions à venir
        $this->sessions = Session::where('ouverture', '>', Carbon::now())
            ->orderBy('ouverture', 'asc')
            ->get();

    }

    // Fonction pour enregistrer le document
    public function save()
    {
        // Validation des champs
        $this->validate([
            'nom' => 'required|string|max:255',
            'session' => 'integer|nullable|exists:sessions_vote,id',
            'description' => 'nullable|string',
            'contenu' => 'required|string',
            'amendement_ouverture' => 'nullable|date',
            'vote_fermeture' => 'nullable|date',
        ]);

        // Création du document en base
        $document = Document::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'session_id' => $this?->session,
            'user_id' => Auth::id(), // On associe l'ID de l'utilisateur connecté
            'amendement_ouverture' => Carbon::parse($this->amendement_ouverture, 'Europe/Paris'),
            'vote_fermeture' => $this->vote_fermeture ? Carbon::parse($this->vote_fermeture, 'Europe/Paris') : null,
        ]);

        // découpage initiale des segments
        $i = 0;
        while($i < strlen($this->contenu)){
            $finSegment = 0;
            $reste = strpos(substr($this->contenu, $i+50), " ");
            $reste = $reste === false ? 0 : $reste;
        
            $positionRetourLigne = strpos(substr($this->contenu, $i ,50+$reste), "\n");
        
            if($positionRetourLigne !== false){
                if($positionRetourLigne != 0){
                    $finSegment = $positionRetourLigne;
                } else {
                    $compteurRetourLigne = 0;
                    while(($i + $compteurRetourLigne) < strlen($this->contenu) && $this->contenu[$i+$compteurRetourLigne] == "\n")
                        $compteurRetourLigne++;
                    $finSegment = $compteurRetourLigne;
                }
            } else {
                $finSegment = 50 + $reste;
            }
        
            if ($finSegment <= 0) {
                $finSegment = min(50, strlen($this->contenu) - $i);
            }
        
            Segment::create([
                'texte' => substr($this->contenu, $i, $finSegment),
                'document_id' => $document->id,
            ]);
            $i += $finSegment;
        }
        
        // Message flash pour la réussite
        session()->flash('success', 'Document créé avec succès.');

        // Redirection vers la liste des documents
        return redirect()->route('documents.index');
    }

    // Fonction de rendu de la vue
    public function render()
    {
        return view('livewire.documents.create');
    }
}
