<?php

namespace App\Livewire\Sessions;

use Carbon\Carbon;
use App\Models\Session;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $sessionsPasse;
    public $sessionsEnCours;
    public $sessionsFutur;

    public function mount()
    {
        $now = Carbon::now();
        $sessions = Session::whereIn('groupe_id', Auth::user()->groupes->pluck('id'))
            ->with('documents')
            ->get();
 
        $this->sessionsPasse = $sessions->filter(function ($session) use ($now) {
            return $session->fermeture !== null && $session->fermeture < $now;
        });

        $this->sessionsEnCours = $sessions->filter(function ($session) use ($now) {
            return $session->ouverture <= $now && ($session->fermeture === null || $session->fermeture >= $now);
        });

        $this->sessionsFutur = $sessions->filter(function ($session) use ($now) {
            return $session->ouverture > $now;
        });
    }

    public function render()
    {
        return view('livewire.sessions.index');
    }
}
