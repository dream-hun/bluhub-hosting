<?php

use App\Http\Middleware\AuthGates;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\TwoFactorMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', [
            AuthGates::class,
        ]);
        $middleware->alias([
            'is_admin' => IsAdmin::class,
            '2fa' => TwoFactorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
