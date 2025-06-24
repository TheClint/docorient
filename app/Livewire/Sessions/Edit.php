<?php

namespace App\Livewire\Sessions;

use App\Models\User;
use App\Models\Session;
use Livewire\Component;

class Edit extends Component
{
    public Session $session;
    public array $commissaires = [];
    public string $search = '';
    public array $searchResults = [];

    public function mount($sessionId)
    {
        $this->session = Session::findOrFail($sessionId);

        $this->loadCommissaires();
    }

    public function loadCommissaires()
    {
        $this->commissaires = collect($this->session->commissaires ?? [])
            ->map(fn ($id) => User::find($id))
            ->filter()
            ->toArray();
    }

    public function updatedSearch($value)
    {
        $this->searchResults = User::where('name', 'like', '%' . $value . '%')
            ->whereNotIn('id', collect($this->commissaires)->pluck('id'))
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function addCommissaire($userId)
    {
        if (!in_array($userId, collect($this->commissaires)->pluck('id')->toArray())) {
            $user = User::find($userId);
            if ($user) {
                $this->commissaires[] = $user;
                $this->updateSession();
                $this->search = '';
                $this->searchResults = [];
            }
        }
    }

    public function removeCommissaire($userId)
    {
        $this->commissaires = array_filter($this->commissaires, fn ($user) => $user['id'] !== $userId);
        $this->updateSession();
    }

    protected function updateSession()
    {
        $this->session->commissaires = collect($this->commissaires)->pluck('id')->toArray();
        $this->session->save();
    }

    public function render()
    {
        return view('livewire.sessions.edit');
    }
}
