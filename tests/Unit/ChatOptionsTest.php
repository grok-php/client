<?php

namespace GrokPHP\Client\Tests\Unit;

use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Enums\DefaultConfig;
use GrokPHP\Client\Enums\Model;
use PHPUnit\Framework\TestCase;

class ChatOptionsTest extends TestCase
{
    public function test_it_applies_default_chat_options(): void
    {
        $options = new ChatOptions;

        $this->assertEquals(Model::from(DefaultConfig::MODEL->value), $options->model);
        $this->assertEquals((float) DefaultConfig::TEMPERATURE->value, $options->temperature);
        $this->assertEquals(DefaultConfig::STREAMING->value === 'true', $options->stream);
    }
}
