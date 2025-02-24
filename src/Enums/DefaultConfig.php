<?php

namespace GrokPHP\Client\Enums;

/**
 * Enum representing available Grok AI models.
 */
enum DefaultConfig: string
{
    case BASE_URI = 'https://api.x.ai/v1/';
    case MODEL = 'grok-2';
    case VISION_MODEL = 'grok-2-vision-1212';
    case TEMPERATURE = '0.7';
    case STREAMING = 'false';
    case TIMEOUT = '30';
}
