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
    protected $listeners = [
        'amendementVote' => 'handleAmendementVote',
    ];

    public function mount(Amendement $amendementId)
    {
        $this->amendement = $amendementId;
        $this->votes = $this->amendement->votes;

        $this->loadData();
    }

    public function handleAmendementVote($id)
    {
        // facultatif : vérifie si $id correspond à l'amendement affiché
        if ($this->amendement->id == $id) {
            // actualise tes données si nécessaire ici
            $this->loadData();

            // Envoie l’événement JS pour Alpine/Chart.js
            $this->dispatch('vote-completed', [
                'pour' => $this->compteurPour,
                'contre' => $this->compteurContre,
                'abstention' => $this->compteurAbstention,
            ]);
        }
    }

    protected function loadData()
    {
        // recharge les données si besoin
        $amendement = Amendement::with('votes')->find($this->amendement->id);
        $this->compteurPour = $amendement->votes()->where('approbation', 'pour')->count();
        $this->compteurContre = $amendement->votes()->where('approbation', 'contre')->count();
        $this->compteurAbstention = $amendement->votes()->where('approbation', 'abstention')->count();
    }

    public function render()
    {
        return view('livewire.amendements.resultat');
    }
}
