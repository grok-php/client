<?php

namespace GrokPHP\Client\Testing;

use GuzzleHttp\Psr7\Response;
use JsonException;

class ClientFake
{
    /**
     * Fake a successful API response for a chat completion request.
     *
     * @throws JsonException
     */
    public static function fakeSuccessResponse(?array $data = null): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => '7c51076a-e4cc-4855-8dbe-66c26818e35f',
            'object' => 'chat.completion',
            'created' => time(),
            'model' => 'grok-2-1212',
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => 'assistant',
                        'content' => json_encode($data ?? [
                            'framework_name' => 'Laravel',
                            'release_date' => '2011',
                            'programming_language' => 'PHP',
                        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT),
                        'refusal' => null,
                    ],
                    'finish_reason' => 'stop',
                ],
            ],
            'usage' => [
                'prompt_tokens' => 72,
                'completion_tokens' => 31,
                'reasoning_tokens' => 0,
                'total_tokens' => 103,
                'prompt_tokens_details' => [
                    'text_tokens' => 72,
                    'audio_tokens' => 0,
                    'image_tokens' => 0,
                    'cached_tokens' => 0,
                ],
            ],
            'system_fingerprint' => 'fp_5c0c5bd9d9',
        ], JSON_THROW_ON_ERROR));
    }
}
