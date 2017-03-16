<?php
declare(strict_types = 1);

namespace Bajcik\Util;

class BooleanUtils
{

    public static function parse($value): bool
    {
        return static::isTrue($value) ? true : false;
    }

    public static function isFalse($value): bool
    {
        return (false === $value) || (0 === $value) || is_string($value) && in_array(StringUtils::toLower($value), [
                '0',
                'false',
                'f',
                'n',
                'no',
                'off',
            ], true);
    }

    public static function isTrue($value): bool
    {
        return (true === $value) || (1 === $value) || is_string($value) && in_array(StringUtils::toLower($value), [
                '1',
                'true',
                't',
                'y',
                'yes',
                'on',
            ], true);
    }

    public static function isValid($value): bool
    {
        return self::isTrue($value) || self::isFalse($value);
    }

}
