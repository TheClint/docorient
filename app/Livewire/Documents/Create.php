<?php

namespace App\Livewire\Documents;


use App\Models\Segment;
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

    // Fonction pour enregistrer le document
    public function save()
    {
        // Validation des champs
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contenu' => 'required|string',
            'amendement_ouverture' => 'nullable|date',
            'vote_fermeture' => 'nullable|date',
        ]);

        // Création du document en base
        $document = Document::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'user_id' => Auth::id(), // On associe l'ID de l'utilisateur connecté
            'amendement_ouverture' => Carbon::parse($this->amendement_ouverture, 'Europe/Paris')->setTimezone('UTC'),
            'vote_fermeture' => $this->vote_fermeture ? Carbon::parse($this->vote_fermeture, 'Europe/Paris')->setTimezone('UTC') : null,
        ]);

        // découpage initiale des segments
        $i = 0;
        while($i < strlen($this->contenu)){
            $finSegment = 0;
            // définition d'un segment de 50 caractère plus le reste du mot entrecoupé par le curseur
            $reste = strpos(substr($this->contenu, $i+50), " ");
            
            // calcul de la position d'un retour à la ligne sur ce segment s'il existe
            $positionRetourLigne = strpos(substr($this->contenu, $i ,50+$reste), "\n");

            // calcul de la fin du segment effectif
            if($positionRetourLigne !== false){
                if($positionRetourLigne != 0){
                    $finSegment = $positionRetourLigne;
                }else{
                    $compteurRetourLigne = 0;
                    while($this->contenu[$i+$compteurRetourLigne] == "\n")
                        $compteurRetourLigne++;
                    $finSegment = $compteurRetourLigne;
                }     
            }
            else
                $finSegment = 50 + $reste;

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
