<?php

namespace GrokPHP\Client\Clients;

use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Config\GrokConfig;
use GrokPHP\Client\Contracts\ClientInterface;
use GrokPHP\Client\Enums\DefaultConfig;
use GrokPHP\Client\Exceptions\GrokException;
use GrokPHP\Client\Traits\HandlesRequests;
use GuzzleHttp\Client;

/**
 * Grok API Client
 */
class GrokClient implements ClientInterface
{
    use HandlesRequests;

    /**
     * Constructs a new instance of GrokClient.
     *
     * @param  GrokConfig  $config  The Grok API configuration.
     */
    public function __construct(
        private readonly GrokConfig $config
    ) {
        $this->apiKey = $config->apiKey;
        $this->httpClient = new Client([
            'base_uri' => $config->baseUri,
            'timeout' => $config->timeout ?? (int) DefaultConfig::TIMEOUT->value,
        ]);
    }

    /**
     * Returns the API key from the configuration.
     */
    public function getApiKey(): string
    {
        return $this->config->apiKey;
    }

    /**
     * Overrides the HTTP client (useful for testing).
     *
     * @param  Client  $client  Custom Guzzle client.
     */
    public function setHttpClient(Client $client): void
    {
        $this->httpClient = $client;
    }

    /**
     * Sends a chat request to Grok API.
     *
     * @param array $messages Chat messages
     * @param ChatOptions $options Chat configuration
     * @return array API response
     * @throws GrokException
     */
    public function chat(array $messages, ChatOptions $options): array
    {
        return $this->sendRequest('chat/completions', [
            'model' => $options->model->value,
            'messages' => $messages,
            'temperature' => $options->temperature,
            'stream' => $options->stream,
        ]);
    }
}
