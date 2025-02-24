<?php

namespace GrokPHP\Client\Tests\Feature;

use GrokPHP\Client\Clients\GrokClient;
use GrokPHP\Client\Clients\Vision;
use GrokPHP\Client\Config\GrokConfig;
use GrokPHP\Client\Enums\Model;
use GrokPHP\Client\Exceptions\GrokException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

class VisionTest extends TestCase
{
    private Vision $vision;

    /**
     * @throws GrokException
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock Configuration
        $config = new GrokConfig(getenv('GROK_API_KEY'));

        // Mock HTTP Client
        $httpClientMock = Mockery::mock(Client::class);
        $httpClientMock->shouldReceive('post')->andReturn(
            new Response(200, [], json_encode(['choices' => [['message' => ['content' => 'This is a dog.']]]]))
        );

        // Mock GrokClient
        $client = Mockery::mock(GrokClient::class, [$config])->makePartial();
        $client->shouldReceive('chat')->andReturnUsing(function ($messages) {
            return ['choices' => [['message' => ['content' => 'Mocked Vision Response']]]];
        });

        $this->vision = new Vision($client);
    }

    /**
     * ✅ Test successful image analysis.
     *
     * @throws GrokException
     */
    public function test_analyze_image_successfully(): void
    {
        $response = $this->vision->analyze('https://www.shutterstock.com/image-photo/young-english-cocker-spaniel-puppy-600nw-2026045151.jpg', 'Describe this image');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('choices', $response);
        $this->assertEquals('Mocked Vision Response', $response['choices'][0]['message']['content']);
    }

    /**
     * ✅ Test invalid model error.
     */
    public function test_throws_exception_for_invalid_model(): void
    {
        $this->expectException(GrokException::class);
        $this->expectExceptionMessage('The model does not support image input but some images are present in the request.');

        $this->vision->analyze('https://www.shutterstock.com/image-photo/young-english-cocker-spaniel-puppy-600nw-2026045151.jpg', 'Describe this image', Model::GROK_2);
    }

    /**
     * ✅ Test missing image error.
     */
    public function test_throws_exception_when_image_not_found(): void
    {
        $this->expectException(GrokException::class);
        $this->expectExceptionMessage('Image file not found or invalid URL: /path/to/nonexistent/image.jpg');

        $this->vision->analyze('/path/to/nonexistent/image.jpg', 'Describe this image');
    }
}
