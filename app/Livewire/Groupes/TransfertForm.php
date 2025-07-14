<?php

namespace App\Livewire\Groupes;

use App\Models\User;
use App\Models\Theme;
use App\Models\Groupe;
use App\Models\Mandat;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TransfertForm extends Component
{
    public Theme $theme;
    public Groupe $groupe;

    public ?int $user_id_to = null;
    public string $type = 'delegation';
    public ?string $fin_at = null;
    public string $searchUser = '';
    public Collection $searchResults;

    public function mount(Theme $themeId, Groupe $groupeId)
    {
        $this->theme = $themeId;
        $this->groupe = $groupeId;
        $this->searchResults = collect();
    }

    public function save()
    {
        $this->validate([
            'user_id_to' => 'required|exists:users,id',
            'type' => 'required|in:delegation,procuration',
            'fin_at' => 'nullable|date|after_or_equal:today',
        ]);

        Mandat::create([
            'user_id_from' => Auth::id(),
            'user_id_to' => $this->user_id_to,
            'groupe_id' => $this->groupe->id,
            'theme_id' => $this->theme->id,
            'type' => $this->type,
            'fin_at' => $this->fin_at ? $this->fin_at : null,
        ]);

        session()->flash('success', 'Mandat créé avec succès.');
        $this->reset(['groupe_id', 'user_id_to', 'type', 'fin_at']);
    }

    public function updatedSearchUser()
    {
        $this->searchResults = User::whereHas('groupes', fn ($q) =>
            $q->where('groupes.id', $this->groupe->id)
        )
        ->where('name', 'like', '%' . $this->searchUser . '%')
        ->limit(5)
        ->get();
    }

    public function selectUser(int $id): void
    {
        $user = User::find($id);
        if (!$user) return;

        $this->user_id_to = $user->id;
        $this->searchUser = $user->name;
        $this->searchResults = collect(); // Vider les résultats après sélection
    }

    public function render()
    {
        return view('livewire.groupes.transfert-form');
    }
}
