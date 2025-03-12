<?php

namespace GrokPHP\Client\Tests\Feature;

use GrokPHP\Client\Clients\GrokClient;
use GrokPHP\Client\Clients\Vision;
use GrokPHP\Client\Config\GrokConfig;
use GrokPHP\Client\Enums\Model;
use GrokPHP\Client\Exceptions\GrokException;
use GrokPHP\Client\Testing\VisionFake;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use JsonException;
use PHPUnit\Framework\TestCase;

class VisionTest extends TestCase
{
    private Vision $vision;

    /**
     * @throws GrokException
     */
    private Vision $visionSuccess;

    private Vision $visionFailure;

    /**
     * @throws GrokException
     * @throws JsonException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $config = new GrokConfig('fake-api-key');

        $successMock = new MockHandler([
            VisionFake::fakeVisionSuccessResponse(),
        ]);
        $successHttpClient = new Client(['handler' => HandlerStack::create($successMock)]);
        $successGrokClient = new GrokClient($config, $successHttpClient);
        $this->visionSuccess = new Vision($successGrokClient);

        $failureMock = new MockHandler([
            VisionFake::fakeVisionInvalidModelResponse(),
            VisionFake::fakeVisionImageNotFoundResponse(),
        ]);
        $failureHttpClient = new Client(['handler' => HandlerStack::create($failureMock)]);
        $failureGrokClient = new GrokClient($config, $failureHttpClient);
        $this->visionFailure = new Vision($failureGrokClient);
    }

    /**
     * ✅ Test successful image analysis.
     *
     * @throws GrokException
     */
    public function test_analyze_image_successfully(): void
    {
        $response = $this->visionSuccess->analyze(
            'https://www.shutterstock.com/image-photo/young-english-cocker-spaniel-puppy-600nw-2026045151.jpg',
            'Describe this image'
        );

        $this->assertArrayHasKey('choices', $response);
        $this->assertSame('assistant', $response['choices'][0]['message']['role']);
        $this->assertStringContainsString('cat', $response['choices'][0]['message']['content']);
    }

    /**
     * ✅ Test invalid model error.
     */
    public function test_throws_exception_for_invalid_model(): void
    {
        $this->expectException(GrokException::class);
        $this->expectExceptionMessage('The model does not support image input but some images are present in the request.');

        $this->visionFailure->analyze(
            'https://www.shutterstock.com/image-photo/young-english-cocker-spaniel-puppy-600nw-2026045151.jpg',
            'Describe this image',
            Model::GROK_2
        );
    }

    /**
     * Test missing image error.
     */
    public function test_throws_exception_when_image_not_found(): void
    {
        $this->expectException(GrokException::class);
        $this->expectExceptionMessage('Error fetching image from URL: https://invalid-url.com/nonexistent.jpg');

        $this->visionFailure->analyze(
            'https://invalid-url.com/nonexistent.jpg',
            'Describe this image'
        );
    }
}
