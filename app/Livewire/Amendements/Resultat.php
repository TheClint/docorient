<?php

namespace App\Livewire\Amendements;

use App\Models\Amendement;
use Livewire\Component;

class Resultat extends Component
{
    public Amendement $amendement;
    public $votes;
    public $compteurPour;
    public $compteurContre;
    public $compteurAbstention;

    public function mount($amendementId)
    {
        $this->amendement = Amendement::find($amendementId);
        $this->votes = $this->amendement->votes;

        $this->compteurPour = 0;
        $this->compteurContre = 0;
        $this->compteurAbstention = 0;

        foreach($this->votes as $vote){
            if($vote->vote->approbation === "pour")
                $this->compteurPour++;
            if($vote->vote->approbation === "contre")
                $this->compteurContre++;
            if($vote->vote->approbation === "abstention")
                $this->compteurAbstention++;
        }
    }

    public function render()
    {
        return view('livewire.amendements.resultat');
    }
}
