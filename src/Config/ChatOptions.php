<?php

namespace GrokPHP\Client\Config;

use GrokPHP\Client\Enums\DefaultConfig;
use GrokPHP\Client\Enums\Model;

/**
 * Chat options for Grok API requests.
 */
class ChatOptions
{
    public Model $model;

    public float $temperature;

    public bool $stream;

    public function __construct(
        ?Model $model = null,
        ?float $temperature = null,
        ?bool $stream = null
    ) {

        $this->model = $model ?: Model::tryFrom(DefaultConfig::MODEL->value) ?: Model::GROK_2;

        $this->temperature = $temperature ?? (float) DefaultConfig::TEMPERATURE->value;

        $this->stream = $stream ?? filter_var(DefaultConfig::STREAMING->value, FILTER_VALIDATE_BOOLEAN);
    }
}
