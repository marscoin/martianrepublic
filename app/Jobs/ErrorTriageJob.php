<?php

namespace App\Jobs;

use App\Mail\ErrorTriageMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ErrorTriageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(
        private readonly string $exceptionClass,
        private readonly string $message,
        private readonly string $file,
        private readonly int $line,
        private readonly string $trace,
        private readonly string $url,
        private readonly string $method,
        private readonly ?string $userId,
        private readonly string $occurredAt,
    ) {}

    public function handle(): void
    {
        $config = config('services.error_triage');
        $apiKey = $config['openrouter_key'];

        if (! $apiKey) {
            Log::warning('ErrorTriage: No OpenRouter API key configured');

            return;
        }

        $prompt = $this->buildPrompt();
        $aiSummary = $this->callOpenRouter($apiKey, $config['model'], $prompt);

        $emails = array_map('trim', explode(',', $config['emails']));

        try {
            Mail::to($emails)->send(new ErrorTriageMail(
                exceptionClass: $this->exceptionClass,
                errorMessage: $this->message,
                file: $this->file,
                line: $this->line,
                url: $this->url,
                method: $this->method,
                userId: $this->userId,
                occurredAt: $this->occurredAt,
                aiSummary: $aiSummary,
            ));

            Log::info('ErrorTriage: Alert sent', ['exception' => $this->exceptionClass, 'file' => $this->file]);
        } catch (\Throwable $e) {
            Log::error('ErrorTriage: Failed to send email', ['error' => $e->getMessage()]);
        }
    }

    private function buildPrompt(): string
    {
        $shortTrace = implode("\n", array_slice(explode("\n", $this->trace), 0, 20));

        return <<<PROMPT
You are a senior Laravel developer triaging a 500 error on the Martian Republic web app (Laravel 11, Marscoin blockchain governance platform).

Analyze this error and provide:
1. **What happened** — one sentence plain-English summary
2. **Likely cause** — what probably triggered this
3. **Suggested fix** — specific code-level fix recommendation
4. **Severity** — Critical / High / Medium / Low

Keep the total response under 200 words. Be direct and actionable.

EXCEPTION: {$this->exceptionClass}
MESSAGE: {$this->message}
FILE: {$this->file}:{$this->line}
URL: {$this->method} {$this->url}
USER ID: {$this->userId}
TIME: {$this->occurredAt}

STACK TRACE (first 20 frames):
{$shortTrace}
PROMPT;
    }

    private function callOpenRouter(string $apiKey, string $model, string $prompt): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'HTTP-Referer' => 'https://martianrepublic.org',
                'X-Title' => 'Martian Republic Error Triage',
            ])->timeout(30)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 400,
                'temperature' => 0.3,
            ]);

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;
            $usedModel = $data['model'] ?? $model;

            if ($content) {
                return $content."\n\n— Triaged by: {$usedModel}";
            }

            Log::warning('ErrorTriage: Empty AI response', ['response' => $data]);

            return 'AI triage unavailable — empty response from '.$model;
        } catch (\Throwable $e) {
            Log::warning('ErrorTriage: AI call failed', ['error' => $e->getMessage()]);

            return 'AI triage unavailable — '.$e->getMessage();
        }
    }
}
