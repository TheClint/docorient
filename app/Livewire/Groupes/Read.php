<?php

namespace App\Livewire\Groupes;

use App\Models\Groupe;
use Livewire\Component;

class Read extends Component
{
    public $groupe;

    public function mount(Groupe $groupeId)
    {
        $this->groupe = $groupeId;
    }

    public function render()
    {
        return view('livewire.groupes.read');
    }
}
