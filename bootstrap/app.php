<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // thêm dòng này
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    ->withMiddleware(function () {
        return [
            // Middleware toàn cục (Global)
            \App\Http\Middleware\CheckUserCandidate::class,
            \App\Http\Middleware\CheckUserEmployer::class,
            \App\Http\Middleware\CheckUserAdmin::class,
        ];
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create()
    ;


    
