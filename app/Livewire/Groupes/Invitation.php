<?php

namespace App\Livewire\Groupes;

use App\Models\GroupeInvitation;
use Illuminate\Support\Str;
use Livewire\Component;

class Invitation extends Component
{
    public string $email = '';
    public int $groupe_id;
    public string $invitationLink = '';

    public function mount(int $groupe_id)
    {
        if (!auth()->user()->groupes->pluck('id')->contains($groupe_id)) {
            abort(403, 'Accès non autorisé à ce groupe.');
        }

        $this->groupe_id = $groupe_id;
    }

    public function invite()
    {
        $this->validate([
            'email' => 'required|email',
            'groupe_id' => 'required|exists:groupes,id',
        ]);

        $token = Str::random(40);

        GroupeInvitation::create([
            'groupe_id' => $this->groupe_id,
            'email' => $this->email,
            'token' => $token,
            'expires_at' => now()->addDays(7),
        ]);

        $this->invitationLink = route('groupe.invitation.accept', $token);
        session()->flash('success', 'Invitation envoyée !');

        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.groupes.invitation');
    }
}
