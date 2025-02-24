<?php

namespace GrokPHP\Client\Utils;

use GrokPHP\Client\Enums\Model;
use GrokPHP\Client\Exceptions\GrokException;

class MessageBuilder
{
    /**
     * Builds messages for Grok API (supports both chat and vision models).
     *
     * @param  string  $prompt  User's input text.
     * @param  string|null  $base64Image  Base64 encoded image (if applicable).
     * @param  Model  $model  Selected Grok AI model.
     * @return array Prepared messages for API request.
     *
     * @throws GrokException
     */
    public static function build(string $prompt, ?string $base64Image, Model $model): array
    {
        if ($base64Image !== null && ! self::supportsVision($model)) {
            throw new GrokException(
                'The model does not support image input but some images are present in the request.',
                400,
                GrokException::ERROR_TYPES['unsupported_model_for_images']
            );
        }

        $content = [['type' => 'text', 'text' => $prompt]];

        if ($base64Image) {
            $content[] = [
                'type' => 'image_url',
                'image_url' => [
                    'url' => "data:image/jpeg;base64,{$base64Image}",
                    'detail' => 'high',
                ],
            ];
        }

        return [['role' => 'user', 'content' => $content]];
    }

    /**
     * Checks if the given model supports vision-based input.
     */
    private static function supportsVision(Model $model): bool
    {
        return in_array($model->value, [
            Model::GROK_2_VISION->value,
            Model::GROK_2_VISION_LATEST->value,
            Model::GROK_2_VISION_1212->value,
        ], true);
    }
}
