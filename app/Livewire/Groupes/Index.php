<?php

namespace App\Livewire\Groupes;

use Livewire\Component;

class Index extends Component
{
    public $selectedGroupeId;
    public $selectedGroupe;
    public $groupes;

    public function mount()
    {
        $this->groupes = auth()->user()->groupes()->orderBy('nom')->get();
    }

    public function updatedSelectedGroupeId($value)
    {
        $this->selectedGroupe = $this->groupes->firstWhere('id', $value);
    }

    public function render()
    {
        return view('livewire.groupes.index');
    }
}
