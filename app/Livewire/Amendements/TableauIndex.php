<?php

namespace App\Livewire\Amendements;

use App\Models\Segment;
use App\Models\Session;
use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;
use Illuminate\Support\Facades\Auth;

class TableauIndex extends Component
{
    public $document;
    public $segments;
    public $amendements;
    public $sortField = 'numero'; // Tri par défaut sur la position du premier segment
    public $sortDirection = 'asc'; // Tri croissant par défaut
    public $numero = []; // création d'un tableau de numero pour conserver l'ordre initial basé sur l'id du premier segment de l'amendement
    public bool $president = false;
    public $mode = "consultation";
    
    public function mount($document)
    {
        $this->document = $document;

        if (Session::find($this->document->session_id)?->user_id === Auth::id()) {
            $this->president = true;
        }
      
        // Récupérer les segments de ce document
        $segmentIds = $this->document->segments->pluck('id');
        
        // Récupérer les amendements associés à ces segments
        $this->amendements = Amendement::whereHas('modifications', function ($query) use ($segmentIds) {
            $query->whereIn('segments.id', $segmentIds);
        })
        ->with(['user', 'statut'])  // Charger les relations 'user' et 'statut' directement
        ->get();

        // Charger le premier segment pour chaque amendement
        
        foreach ($this->amendements as $amendement) {
            $amendement->premierSegment = Segment::whereHas('modifications', function ($query) use ($amendement) {
                $query->where('amendement_id', $amendement->id);
            })
            ->orderBy('id')
            ->first();
        }

        // tri des amendements par rapport à l'id du premier segment de ceux-ci
        $this->amendements = $this->amendements->sortBy("premierSegment");

        // stockage en mémoire de l'ordre ainsi crée
        $compteur = 1;
        foreach ($this->amendements as $amendement) {
            $this->numero[$amendement->id] = $compteur;
            $compteur++;
        }
    }

    // Méthode pour gérer le tri
    public function sortBy($field)
    {
        // Change la direction du tri ou réinitialise le champ de tri
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        // Trie dynamique des amendements par le champ sélectionné
        $this->sortAmendments();
    }

    private function sortAmendments()
    {
        // Tri des amendements basé sur le champ de tri et la direction (asc/desc)
        $this->amendements = $this->amendements->sortBy(function ($amendement) {
            // Si on trie par 'numero', on retourne la valeur de 'numero' pour le tri
            if ($this->sortField === 'numero') {
                return $this->numero[$amendement->id]; // Tri par 'numero' (ordre des amendements dans le texte)
            }
    
            // Si le champ de tri contient une relation, comme 'user.name' ou 'statut.libelle'
            if (strpos($this->sortField, '.') !== false) {
                list($relation, $attribute) = explode('.', $this->sortField);
                return $amendement->{$relation}->{$attribute};
            }
    
            // Sinon, on trie directement par une propriété de l'amendement (ex: 'titre', 'created_at')
            return $amendement->{$this->sortField};
        }, SORT_REGULAR, $this->sortDirection === 'desc');
    }

    public function choixAmendementEnCours($amendementId){
        $this->dispatch('amendementChoisi', $amendementId);
    }
    
    public function render()
    {
        return view('livewire.amendements.tableau-index');
    }
}