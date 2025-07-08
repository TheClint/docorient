<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifieGroupeAcces
{
    public function handle(Request $request, Closure $next): Response
    {
        // On détecte dynamiquement le paramètre modèle (ex: document, amendement, session)
        $routeParams = $request->route()->parameters();
    
        foreach ($routeParams as $key => $model) {
            // Si le modèle a une méthode pour récupérer le groupe
            if (method_exists($model, 'getGroupe')) {
                $groupeId = $model->getGroupe()->id;
                $userGroupIds = Auth::user()->groupes->pluck('id')->toArray();

                if (!in_array($groupeId, $userGroupIds)) {
                    abort(403, 'Accès interdit à cette ressource');
                }

                break; // on s'arrête dès qu'on a validé une ressource
            }
        }

        return $next($request);
    }
}
