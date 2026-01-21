<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // CORS must be first in the middleware stack
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
        
        // We're using stateless Bearer token auth, NOT session-based auth
        // So we don't call statefulApi() which would enable sessions/cookies
        
        // Alias middleware personnalisés
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'vendor.approved' => \App\Http\Middleware\CheckVendorStatus::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'sanctum/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();