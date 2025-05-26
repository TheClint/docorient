<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsPresident
{
    public function handle(Request $request, Closure $next): Response
    {
        $session = Session::findOrFail($request->route('sessionId'));

        // Si la route utilise un modèle automatiquement lié
        if ($session && $session->user_id !== Auth::id()) {
            abort(403, 'Accès réservé au président de séance.');
        }

        return $next($request);
    }
}
