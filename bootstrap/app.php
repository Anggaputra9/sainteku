<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\CheckUserStatus;
use App\Http\Middleware\ValidateSignature;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\SetLocale; // <-- TAMBAHKAN

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // MIDDLEWARE ALIAS
        $middleware->alias([
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'verified' => EnsureEmailIsVerified::class,
            'signed' => ValidateSignature::class,
            'role' => RoleMiddleware::class,
            'user.active' => CheckUserStatus::class,
            'locale' => SetLocale::class, // <--- TAMBAHKAN BARIS INI
    ]);

        // MIDDLEWARE GROUP WEB
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            SetLocale::class, // <-- TAMBAHKAN
        ]);

        // GLOBAL MIDDLEWARE
        $middleware->prepend([
            \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // CSRF EXCEPTION
        $middleware->validateCsrfTokens(except: [
            'login',
            'password/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        });

        $exceptions->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        });

        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Not Found.'], 404);
            }
            return response()->view('pages.errors.error-404', [], 404);
        });
    })->create();
