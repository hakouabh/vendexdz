<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Services\SheetImportService;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })->withMiddleware(function (Middleware $middleware) {

        $middleware->trustProxies(at: '*');
        
        $middleware->encryptCookies(except: []);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->withMiddleware(function (Middleware $middleware) {
    // This API remains the standard in Laravel 12
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
    })->create();
    
return function (Schedule $schedule) {
    $schedule->call(function () {
        app(SheetImportService::class)->update();
    })->everyMinute();
};