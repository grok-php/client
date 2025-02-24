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
}
