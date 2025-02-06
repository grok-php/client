<?php

use GrokPHP\Client\Clients\GrokClient;
use GrokPHP\Client\Config\GrokConfig;
use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Enums\Model;
use GrokPHP\Client\Exceptions\GrokException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

beforeEach(function () {
    $this->validApiKey = 'valid-api-key';
    $this->expiredApiKey = 'expired-api-key';
    $this->invalidApiKey = 'invalid-api-key';
});

/**
 * ✅ Test successful chat request with default model and options.
 */
test('successful chat request returns response', function () {
    // Mock a successful Grok API response
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'choices' => [['message' => ['content' => 'Hello from Grok!']]]
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    // Create a test Grok client
    $config = new GrokConfig($this->validApiKey);
    $client = new GrokClient($config);
    $client->setHttpClient($httpClient);

    // Set default chat options
    $options = new ChatOptions();

    // Call the chat method
    $response = $client->chat(
        [['role' => 'user', 'content' => 'Hello!']],
        $options
    );

    expect($response)->toBeArray();
    expect($response['choices'][0]['message']['content'])->toBe('Hello from Grok!');
});

/**
 * ✅ Test successful chat request with a specific model.
 */
test('successful chat request with specific model', function () {
    // Mock a successful Grok API response
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'choices' => [['message' => ['content' => 'Hello from Grok Vision Beta!']]]
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    // Create a test Grok client
    $config = new GrokConfig($this->validApiKey);
    $client = new GrokClient($config);
    $client->setHttpClient($httpClient);

    // Use a specific model
    $options = new ChatOptions(model: Model::GROK_VISION_BETA);

    // Call the chat method
    $response = $client->chat(
        [['role' => 'user', 'content' => 'Describe an image.']],
        $options
    );

    expect($response)->toBeArray();
    expect($response['choices'][0]['message']['content'])->toBe('Hello from Grok Vision Beta!');
});

/**
 * ❌ Test chat request with an expired API key (should return 402).
 */
test('expired API key results in payment required error', function () {
    // Mock a failed response due to expired API key
    $mock = new MockHandler([
        new Response(402, [], json_encode(['error' => 'Payment Required']))
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    // Create a test Grok client with expired API key
    $config = new GrokConfig($this->expiredApiKey);
    $client = new GrokClient($config);
    $client->setHttpClient($httpClient);

    // Set chat options
    $options = new ChatOptions();

    // Call chat and expect an exception
    expect(fn() => $client->chat([['role' => 'user', 'content' => 'Hello!']], $options))
        ->toThrow(GrokException::class, 'API request failed');
});

/**
 * ❌ Test chat request with an invalid API key (should return 401).
 */
test('invalid API key results in unauthorized error', function () {
    // Mock a failed response due to invalid API key
    $mock = new MockHandler([
        new Response(401, [], json_encode(['error' => 'Unauthorized']))
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    // Create a test Grok client with an invalid API key
    $config = new GrokConfig($this->invalidApiKey);
    $client = new GrokClient($config);
    $client->setHttpClient($httpClient);

    // Set chat options
    $options = new ChatOptions();

    // Call chat and expect an exception
    expect(fn() => $client->chat([['role' => 'user', 'content' => 'Hello!']], $options))
        ->toThrow(GrokException::class, 'API request failed');
});
