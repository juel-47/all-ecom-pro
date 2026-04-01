<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'verified'])
            // ->prefix('admin')
            // ->name('admin.')
            ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // $middleware->prepend(HandleCors::class);
        $middleware->appendToGroup('api', \Illuminate\Http\Middleware\HandleCors::class);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'check.permission' => \App\Http\Middleware\CheckPermission::class,
            'redirect.logged.in' => \App\Http\Middleware\RedirectIfAdminLoggedIn::class,
            'employee.access' => \App\Http\Middleware\EmployeeAccess::class,
            'bkash.config' => \App\Http\Middleware\LoadBkashConfig::class,
            'steadfast.config' => \App\Http\Middleware\LoadSteadfastConfig::class,
        ]);
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
