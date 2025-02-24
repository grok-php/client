<?php

namespace GrokPHP\Client\Tests\Feature;

use GrokPHP\Client\Clients\GrokClient;
use GrokPHP\Client\Config\GrokConfig;
use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Enums\Model;
use GrokPHP\Client\Exceptions\GrokException;
use PHPUnit\Framework\TestCase;

class GrokClientTest extends TestCase
{
    protected GrokClient $client;

    /**
     * @throws GrokException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = new GrokConfig(getenv('GROK_API_KEY'));
        $this->client = new GrokClient($config);
    }

    public function test_chat_request_returns_response(): void
    {
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => 'Hello!'],
        ];

        $options = new ChatOptions(model: Model::GROK_2, temperature: 0.7, stream: false);
        $response = $this->client->chat($messages, $options);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('choices', $response);
    }
}
