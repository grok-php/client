<?php

namespace GrokPHP\Client\Tests\Unit;

use GrokPHP\Client\Config\GrokConfig;
use PHPUnit\Framework\TestCase;

class GrokConfigTest extends TestCase
{
    public function test_it_stores_api_key()
    {
        $config = new GrokConfig('test-key');
        $this->assertEquals('test-key', $config->apiKey);
    }

    public function test_it_uses_default_values()
    {
        $config = new GrokConfig('test-key');
        $this->assertNotEmpty($config->baseUri);
        $this->assertIsNumeric($config->timeout);
    }

    public function test_it_allows_custom_values()
    {
        $customBaseUri = 'https://custom-api.grok.dev';
        $customTimeout = 120;

        // Instantiate GrokConfig with custom values
        $config = new GrokConfig(
            apiKey: 'test-api-key',
            baseUri: $customBaseUri,
            timeout: $customTimeout
        );

        // Assertions
        $this->assertEquals('test-api-key', $config->apiKey);
        $this->assertEquals($customBaseUri, $config->baseUri);
        $this->assertEquals($customTimeout, $config->timeout);
    }
}
