<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Session;

class VerifieSessionEnCours
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $sessionId = $request->route('sessionId');
        $session = Session::find($sessionId);

        if (!$session) {
            abort(404, 'Session non trouvée.');
        }

        $now = Carbon::now();

        $isEnCours = $session->ouverture <= $now && 
                     (is_null($session->fermeture) || $session->fermeture >= $now);

        if (!$isEnCours) {
            abort(403, 'La session n\'est pas en cours.');
        }

        return $next($request);
    }
}
