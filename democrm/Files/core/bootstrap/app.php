<?php

// Clean up any BOM or zero-width spaces from environment variables
foreach ($_ENV as $key => $value) {
    if (is_string($value)) {
        $cleanValue = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $value);
        $cleanValue = trim($cleanValue);
        if ($cleanValue !== $value) {
            $_ENV[$key] = $cleanValue;
            putenv("$key=$cleanValue");
        }
    }
}
foreach ($_SERVER as $key => $value) {
    if (is_string($value)) {
        $cleanValue = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $value);
        $cleanValue = trim($cleanValue);
        if ($cleanValue !== $value) {
            $_SERVER[$key] = $cleanValue;
        }
    }
}

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckStatus;
use App\Http\Middleware\Demo;
use App\Http\Middleware\KycMiddleware;
use App\Http\Middleware\MaintenanceMode;
use App\Http\Middleware\RedirectIfAdmin;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RedirectIfNotAdmin;
use App\Http\Middleware\RegistrationStep;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using:function(){
            Route::namespace('App\Http\Controllers')->group(function(){
                Route::middleware(['web'])
                    ->namespace('Admin')
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));

                    Route::middleware(['web','maintenance'])
                    ->namespace('Gateway')
                    ->prefix('ipn')
                    ->name('ipn.')
                    ->group(base_path('routes/ipn.php'));

                Route::middleware(['web','maintenance'])->prefix('user')->group(base_path('routes/user.php'));
                Route::middleware(['web','maintenance'])->group(base_path('routes/web.php'));

            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web',[
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LanguageMiddleware::class,
            \App\Http\Middleware\ActiveTemplateMiddleware::class,
        ]);

        $middleware->alias([
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            'admin' => RedirectIfNotAdmin::class,
            'admin.guest' => RedirectIfAdmin::class,

            'check.status' => CheckStatus::class,
            'demo' => Demo::class,
            'kyc' => KycMiddleware::class,
            'registration.complete' => RegistrationStep::class,
            'maintenance' => MaintenanceMode::class,
        ]);

        $middleware->validateCsrfTokens(
            except: ['user/deposit','ipn*', 'pusher*']
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function () {
            if (request()->is('api/*')) {
                return true;
            }
        });
        $exceptions->respond(function (Response $response) {
            if ($response->getStatusCode() === 401) {
                if (request()->is('api/*')) {
                    $notify[] = 'Unauthorized request';
                    return response()->json([
                        'remark' => 'unauthenticated',
                        'status' => 'error',
                        'message' => ['error' => $notify]
                    ]);
                }
            }

            return $response;
        });
    })->create();

// ─── Vercel Serverless: redirect writable storage to /tmp ─────────────────────
// Vercel's filesystem is read-only at runtime. All framework writes
// (sessions, compiled views, cache, logs) must go to /tmp.
if (getenv('VERCEL')) {
    $tmpStorage = '/tmp/laravel-storage';
    $app->useStoragePath($tmpStorage);
    foreach (['app/public', 'framework/cache/data', 'framework/sessions', 'framework/views', 'logs'] as $dir) {
        @mkdir("{$tmpStorage}/{$dir}", 0755, true);
    }
}

return $app;
