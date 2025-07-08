<?php

use App\Http\Middleware\IsPresident;
use Illuminate\Foundation\Application;
use App\Http\Middleware\VerifieGroupeAcces;
use App\Http\Middleware\StoreInvitationToken;
use App\Http\Middleware\VerifieSessionEnCours;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'president' => IsPresident::class,
            'session.en.cours' => VerifieSessionEnCours::class,
            'acces.groupe' => VerifieGroupeAcces::class,
        ]);
    
        $middleware->append(StoreInvitationToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
