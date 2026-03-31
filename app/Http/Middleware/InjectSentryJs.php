<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectSentryJs
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->isHtmlResponse($response)) {
            return $response;
        }

        $content = $response->getContent();

        if (str_contains($content, '</head>')) {
            $snippet = $this->sentrySnippet();
            $content = str_replace('</head>', $snippet."\n</head>", $content);
            $response->setContent($content);
        }

        return $response;
    }

    private function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');

        return str_contains($contentType, 'text/html') || empty($contentType);
    }

    private function sentrySnippet(): string
    {
        $dsn = config('sentry.dsn');
        if (! $dsn) {
            return '';
        }

        $environment = config('sentry.environment', 'production');

        return <<<HTML
    <!-- Sentry JS Error Tracking -->
    <script src="https://browser.sentry-cdn.com/9.12.0/bundle.tracing.min.js" crossorigin="anonymous"></script>
    <script>
      Sentry.init({
        dsn: "{$dsn}",
        environment: "{$environment}",
        tracesSampleRate: 0.1,
        ignoreErrors: [
          'ResizeObserver loop',
          'Non-Error promise rejection',
          /^Loading chunk/,
          /Failed to execute 'showModal'/,
          /Error invoking postEvent/,
        ],
      });
    </script>
HTML;
    }
}
