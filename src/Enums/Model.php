<?php

namespace GrokPHP\Client\Enums;

/**
 * Enum representing available Grok AI models.
 */
enum Model: string
{
    case GROK_VISION_BETA = 'grok-vision-beta';
    case GROK_2_VISION = 'grok-2-vision';
    case GROK_2_VISION_LATEST = 'grok-2-vision-latest';
    case GROK_2_VISION_1212 = 'grok-2-vision-1212';
    case GROK_2_1212 = 'grok-2-1212';
    case GROK_2 = 'grok-2';
    case GROK_2_LATEST = 'grok-2-latest';
    case GROK_BETA = 'grok-beta';

    /**
     * Get the default model.
     */
    public static function default(): self
    {
        return self::GROK_2;
    }
}
