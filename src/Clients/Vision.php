<?php

namespace GrokPHP\Client\Clients;

use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Enums\DefaultConfig;
use GrokPHP\Client\Enums\Model;
use GrokPHP\Client\Exceptions\GrokException;
use GrokPHP\Client\Utils\ImageProcessor;
use GrokPHP\Client\Utils\MessageBuilder;

class Vision
{
    public function __construct(
        private readonly GrokClient $client
    ) {}

    /**
     * Analyzes an image using Grok's vision model.
     *
     * @param  string  $imagePathOrUrl  Local file path or an image URL.
     * @param  string  $prompt  Description or query for the image.
     * @param  Model|null  $model  Optional vision model (defaults to GROK_2_VISION_1212).
     * @return array API response.
     *
     * @throws GrokException
     */
    public function analyze(string $imagePathOrUrl, string $prompt, ?Model $model = null): array
    {
        $model = $model ?? Model::fromValue(DefaultConfig::VISION_MODEL->value);
        $base64Image = ImageProcessor::getBase64Image($imagePathOrUrl);
        $messages = MessageBuilder::build($prompt, $base64Image, $model);
        $options = new ChatOptions(model: $model, temperature: 0.7, stream: false);

        return $this->client->chat($messages, $options);
    }
}
