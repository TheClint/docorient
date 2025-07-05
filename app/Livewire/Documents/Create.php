<?php

namespace App\Livewire\Documents;


use App\Models\User;
use App\Models\Groupe;
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
    public $amendement_fermeture;
    public $vote_fermeture;
    public bool $automatique = false;
    public $sessions = [];
    public $session;
    public $groupeId;
    public $groupeCourant;
    public $groupes;
    public $theme;
    public $themes = [];

    public function mount()
    {
        // Récupère toutes les sessions à venir
        $this->sessions = Session::where('ouverture', '>', Carbon::now())
            ->whereIn('groupe_id', Auth::user()->groupes->pluck('id'))
            ->orderBy('ouverture', 'asc')
            ->get();

        $this->groupes = User::find(Auth::id())->groupes;

    }

    public function updatedGroupeId($value)
    {
        $this->groupeCourant = Groupe::with('themes')->find($value);
        $this->theme = null; // reset le thème sélectionné
    }

    public function updatedSession($value)
    {
        $this->groupeCourant = Session::with('groupe.themes')->find($value)->groupe;
        $this->theme = null; // reset le thème sélectionné
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
            'amendement_fermeture' => 'nullable|date',
            'vote_fermeture' => 'nullable|date',
            'theme' => 'required|integer',
        ]);

        $this->vote_fermeture = $this->vote_fermeture ? Carbon::parse($this->vote_fermeture, 'Europe/Paris') : null;

        $dateFinVoteOuDebutSession = $this->vote_fermeture ? $this->vote_fermeture : Session::find($this->session)->ouverture;

        // Remplacement des valeurs par défaut et parsing en format carbon
        if($this->amendement_fermeture === null)
            $this->amendement_fermeture = $dateFinVoteOuDebutSession;
        else
            $this->amendement_fermeture = Carbon::parse($this->amendement_fermeture, 'Europe/Paris');
        
        if($this->amendement_ouverture === null)
            $this->amendement_ouverture = Carbon::now();
        else
            $this->amendement_ouverture = Carbon::parse($this->amendement_ouverture, 'Europe/Paris');
        

        // Validation par rapport aux dates
        if($this->vote_fermeture && $this->vote_fermeture < Carbon::now()){
            session()->flash('error', 'Le vote doit être dans l\'avenir');
            return;
        }

        if($this->amendement_fermeture < $this->amendement_ouverture){
            session()->flash('error', 'La fermeture des amendements doit se faire après leur ouverture');
            return;
        }

        if($this->amendement_fermeture > $dateFinVoteOuDebutSession){
            session()->flash('error', 'Les amendements ne peuvent pas être proposé après le vote');
            return;
        }

        // Création du document en base
        $document = Document::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'session_id' => $this?->session,
            'user_id' => Auth::id(), // On associe l'ID de l'utilisateur connecté
            'amendement_ouverture' =>$this->amendement_ouverture,
            'amendement_fermeture' => $this->amendement_fermeture,
            'vote_fermeture' => $this->vote_fermeture,
            'theme_id' => $this->theme,
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
