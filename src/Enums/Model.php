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

    public static function fromValue(string $value): self
    {
        return match ($value) {
            self::GROK_2_VISION_1212->value => self::GROK_2_VISION_1212,
            self::GROK_2_VISION->value => self::GROK_2_VISION,
            self::GROK_2_VISION_LATEST->value => self::GROK_2_VISION_LATEST,
            self::GROK_2_1212->value => self::GROK_2_1212,
            self::GROK_2->value => self::GROK_2,
            self::GROK_2_LATEST->value => self::GROK_2_LATEST,
            self::GROK_BETA->value => self::GROK_BETA,
            self::GROK_VISION_BETA->value => self::GROK_VISION_BETA,
            default => throw new \InvalidArgumentException('Invalid model value'),
        };
    }
}
