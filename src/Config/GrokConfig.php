<?php

namespace GrokPHP\Client\Config;

use GrokPHP\Client\Enums\DefaultConfig;

/**
 * Configuration class for Grok API.
 */
class GrokConfig
{
    public function __construct(
        public readonly string $apiKey,
        public readonly string $baseUri = DefaultConfig::BASE_URI->value,
        public int $timeout = 0
    ) {
        $this->timeout = (int) DefaultConfig::TIMEOUT->value;
    }
}
