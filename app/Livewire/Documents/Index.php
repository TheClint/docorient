<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\Groupe;
use App\Models\Theme;

class Index extends Component
{
    use WithPagination;

    public $selectedGroupe = '';
    public $selectedTheme = '';
    public $groupes = [];
    public $themes = [];

    public function mount()
    {
        $this->groupes = Auth::user()->groupes;
    }

    public function updatedSelectedGroupe()
    {
        $this->resetPage();
        $this->selectedTheme = '';
        $this->themes = Theme::where('groupe_id', $this->selectedGroupe)->get();
    }

    public function updatedSelectedTheme()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Document::query()
            ->whereHas('theme.groupe', function ($q) {
                $q->whereIn('id', Auth::user()->groupes->pluck('id'));
            });

        if ($this->selectedGroupe) {
            $query->whereHas('theme', fn($q) =>
                $q->where('groupe_id', $this->selectedGroupe)
            );
        }

        if ($this->selectedTheme) {
            $query->where('theme_id', $this->selectedTheme);
        }

        $documents = $query->latest()->paginate(9); // 3 lignes de 3 cartes

        return view('livewire.documents.index', [
            'documents' => $documents,
        ]);
    }
}
