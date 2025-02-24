<?php

namespace GrokPHP\Client\Config;

use GrokPHP\Client\Enums\DefaultConfig;
use GrokPHP\Client\Exceptions\GrokException;

/**
 * Configuration class for Grok API.
 */
class GrokConfig
{
    /**
     * @throws GrokException
     */
    public function __construct(
        public ?string $apiKey = null,
        public readonly string $baseUri = DefaultConfig::BASE_URI->value,
        public int $timeout = 0
    ) {
        $this->apiKey = $apiKey ?? getenv('GROK_API_KEY');

        if (! $this->apiKey) {
            throw GrokException::missingApiKey();
        }
        $this->timeout = $timeout ?? (int) DefaultConfig::TIMEOUT->value;
    }
}
