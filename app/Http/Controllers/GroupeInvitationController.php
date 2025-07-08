<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\GroupeInvitation;

class GroupeInvitationController extends Controller
{
    public function accept(string $token)
    {
        $invitation = GroupeInvitation::where('token', $token)
            ->where('used', false)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })->firstOrFail();

        if (!Auth::check()) {
            session(['pending_invitation_token' => $token]);
            return redirect()->route('login'); // Ou register selon le cas
        }

        $user = Auth::user();

        if ($user->email !== $invitation->email) {
            abort(403, 'Ce lien est réservé à une autre adresse e-mail.');
        }

        // Relation many-to-many : utiliser la méthode groupe() avec les parenthèses
        if (!$user->groupes()->where('groupes.id', $invitation->groupe_id)->exists()) {
            $user->groupes()->attach($invitation->groupe_id);
        }

        $invitation->used = true;
        $invitation->save();

        return redirect()->route('dashboard')->with('success', 'Vous avez rejoint le groupe.');
    }
}
