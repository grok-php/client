<?php

namespace GrokPHP\Client\Utils;

use GrokPHP\Client\Exceptions\GrokException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ImageProcessor
{
    /**
     * Processes an image (either from local path or URL) and returns it as a Base64 string.
     *
     * @param  string  $imagePathOrUrl  Image path or URL.
     * @return string Base64 encoded image.
     *
     * @throws GrokException If the image cannot be fetched or processed.
     */
    public static function getBase64Image(string $imagePathOrUrl): string
    {
        if (filter_var($imagePathOrUrl, FILTER_VALIDATE_URL)) {
            return self::fetchImageFromUrl($imagePathOrUrl);
        }

        if (file_exists($imagePathOrUrl)) {
            return base64_encode(file_get_contents($imagePathOrUrl));
        }

        throw new GrokException("Image file not found or invalid URL: {$imagePathOrUrl}", 400, GrokException::ERROR_TYPES['invalid_request']);
    }

    /**
     * Fetches an image from a URL and converts it to Base64.
     *
     * @throws GrokException
     */
    private static function fetchImageFromUrl(string $imageUrl): string
    {
        try {
            $httpClient = new Client;
            $response = $httpClient->get($imageUrl, ['http_errors' => false]);

            if ($response->getStatusCode() !== 200) {
                throw new GrokException("Failed to fetch image from URL: {$imageUrl}", 400, GrokException::ERROR_TYPES['invalid_request']);
            }

            return base64_encode($response->getBody()->getContents());
        } catch (\Exception|GuzzleException $e) {
            throw new GrokException("Error fetching image from URL: {$imageUrl}", 400, GrokException::ERROR_TYPES['invalid_request']);
        }
    }
}
