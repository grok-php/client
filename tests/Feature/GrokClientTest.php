<?php

namespace GrokPHP\Client\Tests\Feature;

use GrokPHP\Client\Clients\GrokClient;
use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Config\GrokConfig;
use GrokPHP\Client\Enums\Model;
use GrokPHP\Client\Exceptions\GrokException;
use GrokPHP\Client\Testing\ClientFake;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use JsonException;
use PHPUnit\Framework\TestCase;

class GrokClientTest extends TestCase
{
    protected GrokClient $client;

    /**
     * @throws GrokException
     * @throws JsonException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $mock = new MockHandler([
            ClientFake::fakeSuccessResponse(),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $config = new GrokConfig('fake-api-key');
        $this->client = new GrokClient($config, $httpClient);
    }

    /**
     * @throws GrokException|JsonException
     */
    public function test_chat_request_returns_response(): void
    {
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => 'Hello!'],
        ];

        $options = new ChatOptions(model: Model::GROK_2, temperature: 0.7, stream: false);
        $response = $this->client->chat($messages, $options);

        $this->assertArrayHasKey('choices', $response);
        $this->assertSame('assistant', $response['choices'][0]['message']['role']);

        $decodedContent = json_decode($response['choices'][0]['message']['content'], true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('Laravel', $decodedContent['framework_name']);
        $this->assertSame('2011', $decodedContent['release_date']);
        $this->assertSame('PHP', $decodedContent['programming_language']);
    }

    public function test_chat_request_returns_response_in_specific_format(): void
    {
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => 'Return a json object with a random name (string) and random city (string).'],
        ];

        $options = new ChatOptions(model: Model::GROK_2_1212, temperature: 0.7, stream: false, responseFormat: ['type' => 'json_object']);
        $response = $this->client->chat($messages, $options);
        $content = $response['choices'][0]['message']['content'];
        $contentArray = json_decode($content, true);

        $this->assertJson($content);
        $this->assertArrayHasKey('name', $contentArray);
        $this->assertArrayHasKey('city', $contentArray);
    }
}
