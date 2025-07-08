<?php

namespace App\Livewire\Amendements;

use Livewire\Component;
use App\Models\Document;

class Index extends Component
{
    public $document;

    public function mount(Document $documentId)
    {
        $this->document = $documentId;
    }
    
    public function render()
    {
        return view('livewire.amendements.index');
    }
}
