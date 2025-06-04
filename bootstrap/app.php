<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsPresident;
use App\Http\Middleware\VerifieSessionEnCours;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['president' => IsPresident::class]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['session.en.cours' => VerifieSessionEnCours::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
