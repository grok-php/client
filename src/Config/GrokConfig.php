<?php

namespace GrokPHP\Client\Config;

/**
 * Configuration class for Grok API.
 */
class GrokConfig
{
    public function __construct(
        public readonly string $apiKey,
        public readonly string $baseUri = 'https://api.x.ai/v1/' 
    ) {}
}
