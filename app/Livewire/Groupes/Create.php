<?php

namespace App\Livewire\Groupes;

use App\Models\Theme;
use App\Models\Groupe;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $nom;

    public function save()
    {
        $this->validate([
            'nom' => 'required|string|max:255|unique:groupes,nom',
        ]);

        $groupe = Groupe::create([
            'nom' => $this->nom,
            'primus_inter_pares' => Auth::id(),
        ]);

        $groupe->membres()->syncWithoutDetaching([
            Auth::id() => []
        ]);

        $theme = Theme::create([
            'nom' => 'general',
            'groupe_id' => $groupe->id,
        ]);

        $theme->save();

        $groupe->save();

        // Message flash pour la réussite
        session()->flash('success', 'Groupe créé avec succès.');

        // Redirection vers la liste des groupes
        return redirect()->route('groupes.index');
    }

    public function render()
    {
        return view('livewire.groupes.create');
    }
}
