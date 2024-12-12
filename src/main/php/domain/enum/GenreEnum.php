<?php

declare(strict_types=1);

namespace domain\enum;

use InvalidArgumentException;
use util\Enumeration;


/**
 * Contains genre types.
 */
class GenreEnum extends Enumeration
{
    //-------------------------------------------------------------------------
    //        Enumerations
    //-------------------------------------------------------------------------
    public const MALE = '0';
    public const FEMALE = '1';

    public static function fromString(string $value): GenreEnum
    {
        $values = [self::MALE, self::FEMALE];

        if (!in_array($value, $values, true)) {
            throw new InvalidArgumentException("Invalid value for GenreEnum: $value");
        }

        return new self($value);
    }
}
