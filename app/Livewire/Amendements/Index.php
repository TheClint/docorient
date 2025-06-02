<?php

namespace App\Livewire\Amendements;

use Livewire\Component;
use App\Models\Document;

class Index extends Component
{
    public $document;

    public function mount($documentId)
    {
        $this->document = Document::find($documentId);
    }
    
    public function render()
    {
        return view('livewire.amendements.index');
    }
}
