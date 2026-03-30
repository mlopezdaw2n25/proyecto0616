<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException; // <--- Esta es vital
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // Esta es la forma más robusta en Laravel 11 de interceptar el error de login
        $exceptions->shouldRenderJsonWhen(fn ($request, $e) => $request->expectsJson());

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            // Si no está logueado, lanzamos el error 401 manualmente
            // Esto obligará a Laravel a buscar resources/views/errors/401.blade.php
            abort(401);
        });
        
    })->create();