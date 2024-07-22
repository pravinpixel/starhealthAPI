<?php
use App\Http\Middleware\JWTExceptionHandler;
use App\Http\Middleware\CheckReferer;
use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('auth', [
            Authenticate::class,
        ]);
        $middleware->group('api', [
            CheckReferer::class,
        ]);
        $middleware->group('auth:api', [
            JWTExceptionHandler::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 404, 'message' => 'not_found'], 404);
             } 
            //  else {
            //     return parent::render($request, $e);
            // }          
        });
    })
    ->create();
