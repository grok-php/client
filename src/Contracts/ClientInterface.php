<?php

namespace GrokPHP\Client\Contracts;

use GrokPHP\Client\Config\ChatOptions;
use GuzzleHttp\Client;

/**
 * Interface for Grok API client.
 */
interface ClientInterface
{
    /**
     * add setHttpClient method
     */
    public function setHttpClient(Client $client): void;

    /**
     * Sends a chat request to the Grok API.
     *
     * @param array $messages Chat messages in API format.
     * @param ChatOptions $options Additional request options.
     * @return array The API response.
     */
    public function chat(array $messages, ChatOptions $options): array;
}
