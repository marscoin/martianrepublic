<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ErrorTriageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $exceptionClass,
        public readonly string $errorMessage,
        public readonly string $file,
        public readonly int $line,
        public readonly string $url,
        public readonly string $method,
        public readonly ?string $userId,
        public readonly string $occurredAt,
        public readonly string $aiSummary,
    ) {}

    public function envelope(): Envelope
    {
        $short = class_basename($this->exceptionClass);
        $severity = $this->extractSeverity();

        return new Envelope(
            subject: "[{$severity}] 500 Error: {$short} — {$this->method} {$this->url}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.error-triage',
        );
    }

    private function extractSeverity(): string
    {
        if (preg_match('/\*\*Severity\*\*\s*[—–:\-]\s*(Critical|High|Medium|Low)/iu', $this->aiSummary, $m)) {
            return strtoupper($m[1]);
        }

        return 'ERROR';
    }
}
