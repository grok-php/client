<?php

namespace GrokPHP\Client\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use stdClass;

/**
 * Class GrokException
 * A centralized exception handler for the Grok PHP Client.
 */
class GrokException extends Exception
{
    /**
     * Define the three main error types.
     */
    const ERROR_TYPES = [
        'invalid_request' => 'Invalid Request',
        'authentication_error' => 'Authentication Error',
        'invalid_api_key' => 'Invalid API Key',
    ];

    /**
     * Map error types to their corresponding HTTP status codes.
     */
    const HTTP_STATUS_CODES = [
        'invalid_request' => 422, // JSON deserialization error
        'authentication_error' => 400, // No API key provided
        'invalid_api_key' => 400, // Incorrect API key
    ];

    /**
     * Constructor.
     */
    public function __construct(
        string $message,
        int $code,
        protected string $type = 'invalid_request',
        protected array $headers = [],
        protected ?stdClass $responseBody = null
    ) {
        parent::__construct($message, $code);
    }

    /**
     * Get the error type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the response headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get the API response body.
     */
    public function getResponseBody(): ?stdClass
    {
        return $this->responseBody;
    }

    /**
     * Convert the exception details to an array.
     *
     * @throws JsonException
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'type' => $this->getType(),
            'code' => $this->getCode(),
            'headers' => $this->getHeaders(),
            'response_body' => $this->responseBody ? json_decode(json_encode($this->responseBody, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR) : null,
        ];
    }

    /**
     * Convert the exception details to JSON.
     *
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

    /**
     * Create a `GrokException` from an API response.
     *
     * @throws JsonException
     */
    public static function fromResponse(ResponseInterface $response): self
    {
        $body = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $errorMessage = $body['error'] ?? 'Unknown error occurred';
        $statusCode = $response->getStatusCode();

        // Determine the error type based on the API response
        $errorType = match ($statusCode) {
            400 => match (true) {
                str_contains(strtolower($errorMessage), 'incorrect api key') => 'invalid_api_key',
                str_contains(strtolower($errorMessage), 'no api key provided') => 'authentication_error',
                default => 'invalid_request',
            },
            422 => 'invalid_request',
            default => 'invalid_request',
        };

        return new self(
            $errorMessage,
            $statusCode,
            $errorType,
            $response->getHeaders(),
            json_decode(json_encode($body, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * Handle exceptions from Guzzle.
     *
     * @throws JsonException
     */
    public static function fromGuzzleException(ClientException $exception): self
    {
        return self::fromResponse($exception->getResponse());
    }

    /**
     * Create an exception for a missing API key.
     */
    public static function missingApiKey(): self
    {
        return new self(
            'No API key provided. Specify your API key in an Authorization header using Bearer auth.',
            400,
            'authentication_error'
        );
    }

    /**
     * Create an exception for an invalid API key.
     */
    public static function invalidApiKey(): self
    {
        return new self(
            'Incorrect API key provided. Obtain an API key from https://console.x.ai.',
            400,
            'invalid_api_key'
        );
    }

    /**
     * Create an exception for an invalid request (JSON deserialization error).
     */
    public static function invalidRequest(): self
    {
        return new self(
            'Failed to deserialize the JSON body into the target type. Ensure request structure is correct.',
            422,
            'invalid_request'
        );
    }
}
