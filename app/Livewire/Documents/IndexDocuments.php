<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\Document;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class IndexDocuments extends Component
{
    public function render()
    {
        $documents = Document::latest()->get();

        return view('livewire.documents.index-documents', compact('documents'));
    }
}

