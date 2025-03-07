<?php

namespace GrokPHP\Client\Testing;

use GuzzleHttp\Psr7\Response;
use JsonException;

class VisionFake
{
    /**
     * Fake a successful API response for Vision analysis.
     *
     * @throws JsonException
     */
    public static function fakeVisionSuccessResponse(): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => '0b0fa149-6125-4bb8-a320-8b8c6cd24dec',
            'object' => 'chat.completion',
            'created' => time(),
            'model' => 'grok-2-vision-1212',
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => 'assistant',
                        'content' => 'The image shows a cat and a dog interacting affectionately. The cat, which is gray with some white patches, is nuzzling or rubbing its head against the dog. The dog, which appears to be a golden retriever, is lying down and looking at the cat. The scene is set indoors with a soft, natural light coming from a window in the background.',
                        'refusal' => null,
                    ],
                    'finish_reason' => 'stop',
                ],
            ],
            'usage' => [
                'prompt_tokens' => 269,
                'completion_tokens' => 74,
                'total_tokens' => 343,
            ],
            'system_fingerprint' => 'fp_08923a0247',
        ], JSON_THROW_ON_ERROR));
    }

    /**
     * Fake a failed API response for Vision analysis.
     *
     * @throws JsonException
     */
    public static function fakeVisionInvalidModelResponse(): Response
    {
        return new Response(400, ['Content-Type' => 'application/json'], json_encode([
            'code' => 'Client specified an invalid argument',
            'error' => 'The model does not support image input but some images are present in the request.',
        ], JSON_THROW_ON_ERROR));
    }

    /**
     * Fake a failed API response when an image is not found.
     *
     * @throws JsonException
     */
    public static function fakeVisionImageNotFoundResponse(): Response
    {
        return new Response(400, ['Content-Type' => 'application/json'], json_encode([
            'code' => 'Unrecoverable data loss or corruption',
            'error' => 'Failed to fetch image from URL',
        ], JSON_THROW_ON_ERROR));
    }
}
