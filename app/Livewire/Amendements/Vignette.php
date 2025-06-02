<?php

namespace App\Livewire\Amendements;

use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;

class Vignette extends Component
{
    public Document $document;
    public Amendement $amendement;

    public function mount(Document $document, Amendement $amendement)
    {
        $this->document = $document;
        $this->amendement = $amendement;
    }

    public function render()
    {
        return view('livewire.amendements.vignette');
    }
}
