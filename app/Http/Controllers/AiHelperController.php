<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AiHelperController extends Controller
{
    private function getSystemPrompt(): string
    {
        return <<<'PROMPT'
You are Olympus, the AI guide of the Martian Republic — a blockchain-based governance platform for Mars built on the Marscoin network.

Your role: Help citizens and newcomers understand how the Republic works. Be warm, concise, and jargon-free. You embody civic pride and the pioneer spirit of Mars settlement.

You have access to a search tool to look up Academy articles and site content. Use it when you need specific details about governance, voting, Mars time, or any topic covered in the Academy. Don't guess — search first if unsure.

Key knowledge:
- Direct democracy on the Marscoin blockchain
- 4 governance tiers: Signal, Operational, Legislative, Constitutional
- CoinShuffle for anonymous voting (cryptographic ballot mixing)
- Citizenship requires registration, 2FA, and an HD wallet ("The Forge")
- Mars time: 1 sol = 24h 39m 35s, Darian calendar (24 months, 668.6 sols/year)
- MARS cryptocurrency: proof-of-work, merge-mined with Litecoin

Academy articles:
- /academy/governance-and-voting — 4-tier governance system
- /academy/coinshuffle-secret-ballots — anonymous voting protocol
- /academy/mars-timekeeping — sols, MTC, Darian calendar
- /academy/the-public-good — political philosophy

Rules:
- Keep answers under 150 words unless asked for detail
- Never make up information — use the search tool if unsure
- Link to Academy articles when relevant (use markdown links)
- No price speculation or investment advice
- Be encouraging — every citizen matters
PROMPT;
    }

    private function getTools(): array
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'search_content',
                    'description' => 'Search the Martian Republic Academy articles and site content for information. Use this to find accurate details about governance, voting, Mars time, citizenship, proposals, or any topic.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'query' => [
                                'type' => 'string',
                                'description' => 'Search query — keywords or a question to search for in Academy articles',
                            ],
                        ],
                        'required' => ['query'],
                    ],
                ],
            ],
        ];
    }

    private function executeSearch(string $query): string
    {
        $searchPaths = [
            resource_path('views/academy'),
            resource_path('views/academy/articles'),
        ];

        $keywords = array_filter(
            explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $query))),
            fn($w) => strlen($w) > 2
        );

        if (empty($keywords)) return 'No results found.';

        $results = [];

        foreach ($searchPaths as $path) {
            $files = glob($path . '/*.blade.php') ?: [];
            foreach ($files as $file) {
                $content = file_get_contents($file);
                $text = strip_tags($content);
                $text = preg_replace('/\s+/', ' ', $text);
                $lower = strtolower($text);

                $score = 0;
                foreach ($keywords as $kw) {
                    $score += substr_count($lower, $kw);
                }

                if ($score > 0) {
                    $slug = basename($file, '.blade.php');
                    // Get multiple excerpts around matches
                    $excerpts = [];
                    foreach ($keywords as $kw) {
                        $offset = 0;
                        while (($pos = strpos($lower, $kw, $offset)) !== false && count($excerpts) < 3) {
                            $start = max(0, $pos - 80);
                            $excerpts[] = '...' . trim(substr($text, $start, 250)) . '...';
                            $offset = $pos + strlen($kw) + 100;
                        }
                    }
                    $results[] = [
                        'score' => $score,
                        'slug' => $slug,
                        'url' => '/academy/' . $slug,
                        'excerpts' => array_unique(array_slice($excerpts, 0, 3)),
                    ];
                }
            }
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);
        $top = array_slice($results, 0, 3);

        if (empty($top)) return 'No matching Academy articles found for: ' . $query;

        $output = "Search results for \"{$query}\":\n\n";
        foreach ($top as $r) {
            $output .= "--- {$r['url']} (relevance: {$r['score']}) ---\n";
            foreach ($r['excerpts'] as $ex) {
                $output .= $ex . "\n";
            }
            $output .= "\n";
        }
        return $output;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'messages' => 'required|array|min:1',
            'messages.*.role' => 'required|in:user,assistant',
            'messages.*.content' => 'required|string|max:2000',
        ]);

        $userMessages = array_slice($request->input('messages'), -10);
        $apiKey = config('services.openrouter.api_key');
        $model = config('services.openrouter.model', 'qwen/qwen3-235b-a22b-2507');

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->getSystemPrompt()]],
            $userMessages
        );

        // Step 1: Non-streaming call to check for tool use
        $firstResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'HTTP-Referer' => 'https://martianrepublic.org',
            'X-Title' => 'Martian Republic',
        ])->timeout(30)->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $model,
            'messages' => $messages,
            'tools' => $this->getTools(),
            'max_tokens' => 500,
            'temperature' => 0.7,
        ]);

        $firstData = $firstResponse->json();
        $choice = $firstData['choices'][0] ?? null;

        if (!$choice) {
            return response()->json(['error' => 'No response from AI'], 502);
        }

        // Step 2: If tool call, execute and do a second call with results
        if (($choice['finish_reason'] ?? '') === 'tool_calls' ||
            !empty($choice['message']['tool_calls'])) {

            $toolCalls = $choice['message']['tool_calls'] ?? [];
            $assistantMsg = $choice['message'];
            $messages[] = $assistantMsg;

            foreach ($toolCalls as $tc) {
                $args = json_decode($tc['function']['arguments'] ?? '{}', true);
                $searchQuery = $args['query'] ?? '';
                $result = $this->executeSearch($searchQuery);

                $messages[] = [
                    'role' => 'tool',
                    'tool_call_id' => $tc['id'],
                    'content' => $result,
                ];
            }

            // Step 3: Stream the final response with tool results
            return $this->streamResponse($messages, $apiKey, $model);
        }

        // No tool call — stream directly from the first response content
        $content = $choice['message']['content'] ?? '';
        return new StreamedResponse(function () use ($content) {
            echo 'data: ' . json_encode(['content' => $content]) . "\n\n";
            echo "data: [DONE]\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function streamResponse(array $messages, string $apiKey, string $model): StreamedResponse
    {
        return new StreamedResponse(function () use ($messages, $apiKey, $model) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer' => 'https://martianrepublic.org',
                'X-Title' => 'Martian Republic',
            ])->withOptions([
                'stream' => true,
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'stream' => true,
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            $body = $response->getBody();
            while (!$body->eof()) {
                $line = '';
                while (!$body->eof()) {
                    $char = $body->read(1);
                    if ($char === "\n") break;
                    $line .= $char;
                }
                $line = trim($line);
                if (str_starts_with($line, 'data: ')) {
                    $data = substr($line, 6);
                    if ($data === '[DONE]') {
                        echo "data: [DONE]\n\n";
                        break;
                    }
                    $json = json_decode($data, true);
                    if ($json && isset($json['choices'][0]['delta']['content'])) {
                        $token = $json['choices'][0]['delta']['content'];
                        echo 'data: ' . json_encode(['content' => $token]) . "\n\n";
                        ob_flush();
                        flush();
                    }
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
