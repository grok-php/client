<?php

namespace GrokPHP\Client\Enums;

/**
 * Enum representing the roles in a chat.
 */
enum Role: string
{
    case USER = 'user';
    case SYSTEM = 'system';
}
