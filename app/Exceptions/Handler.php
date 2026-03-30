<?php

namespace App\Exceptions;

use App\Jobs\ErrorTriageJob;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Cache;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Sentry (existing)
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }

            // AI Error Triage — only for 500-level exceptions
            $this->triageError($e);
        });
    }

    /**
     * Dispatch AI triage for server errors, with deduplication.
     */
    private function triageError(Throwable $e): void
    {
        // Skip if no API key configured
        if (! config('services.error_triage.openrouter_key')) {
            return;
        }

        // Skip non-500 exceptions (validation, auth, 404, etc.)
        $skipExceptions = [
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
            \Illuminate\Validation\ValidationException::class,
            \Symfony\Component\HttpKernel\Exception\HttpException::class,
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
            \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
        ];

        foreach ($skipExceptions as $type) {
            if ($e instanceof $type) {
                return;
            }
        }

        // Dedup: same exception class + file + line = same error
        $fingerprint = md5(get_class($e) . $e->getFile() . $e->getLine());
        $cacheKey = 'error_triage:' . $fingerprint;
        $cooldown = (int) config('services.error_triage.cooldown_minutes', 15);

        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, now()->addMinutes($cooldown));

        // Gather request context
        $request = request();
        $url = $request ? $request->fullUrl() : 'CLI';
        $method = $request ? $request->method() : 'CLI';
        $userId = $request?->user()?->id;

        ErrorTriageJob::dispatch(
            exceptionClass: get_class($e),
            message: $e->getMessage(),
            file: $e->getFile(),
            line: $e->getLine(),
            trace: $e->getTraceAsString(),
            url: $url,
            method: $method,
            userId: $userId ? (string) $userId : null,
            occurredAt: now()->toDateTimeString(),
        );
    }
}
