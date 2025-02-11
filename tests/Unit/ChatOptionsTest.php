<?php

namespace GrokPHP\Client\Tests\Unit;

use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Enums\DefaultConfig;
use GrokPHP\Client\Enums\Model;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ChatOptionsTest extends TestCase
{
    #[Test]
    public function it_applies_default_chat_options()
    {
        $options = new ChatOptions;

        expect($options->model)->toBe(Model::tryFrom(DefaultConfig::MODEL->value) ?? Model::GROK_2);
        expect($options->temperature)->toBe((float) DefaultConfig::TEMPERATURE->value);
        expect($options->stream)->toBe(DefaultConfig::STREAMING->value === 'true');
    }
}
