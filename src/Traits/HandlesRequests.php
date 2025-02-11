<?php

namespace GrokPHP\Client\Traits;

use GrokPHP\Client\Exceptions\GrokException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Trait HandlesRequests
 * Provides methods to send API requests.
 */
trait HandlesRequests
{
    protected Client $httpClient;

    protected string $apiKey;

    /**
     * Sends a request to the Grok API.
     *
     * @param  string  $endpoint  API endpoint
     * @param  array  $payload  Request payload
     * @return array API response
     *
     * @throws GrokException If the request fails
     */
    public function sendRequest(string $endpoint, array $payload): array
    {
        try {
            $response = $this->httpClient->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            return json_decode($response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR);
        } catch (RequestException $e) {
            throw new GrokException('API request failed: '.$e->getMessage());
        }
    }
}
