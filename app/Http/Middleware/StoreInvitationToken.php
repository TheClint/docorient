<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreInvitationToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('invitation')) {
            session(['pending_invitation_token' => $request->get('invitation')]);
        }

        return $next($request);
    }
}

