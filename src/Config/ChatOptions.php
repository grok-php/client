<?php

namespace GrokPHP\Client\Config;

use GrokPHP\Client\Enums\Model;

/**
 * Chat options for Grok API requests.
 */
class ChatOptions
{
    public function __construct(
        public readonly Model $model = Model::GROK_2,
        public readonly float $temperature = 0.0,
        public readonly bool $stream = false
    ) {}
}
