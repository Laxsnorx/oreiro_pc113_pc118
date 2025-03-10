<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AllowedRolesMiddleware; //! new

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => AllowedRolesMiddleware::class, //! new
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
    ////////////////
$app->router->group([
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'api',
], function ($router) {
    require base_path('routes/api.php');
});
$app->middleware([
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
]);

