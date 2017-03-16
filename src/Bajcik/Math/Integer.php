<?php
declare(strict_types = 1);

namespace Bajcik\Math;

/**
 *
 * @author Paweł Cichorowski <pawel.cichorowski@gmail.com>
 *
 */
class Integer
{

    /**
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public static function isValid($value): bool
    {
        if (is_int($value)) {
            return true;
        }

        return preg_match('/^\s*[\-+]?\d+\s*$/', $value) > 0;
    }

    /**
     *
     * @param mixed $value
     * @param integer $default
     *
     * @return integer
     */
    public static function parse($value, int $default = 0): int
    {
        if (!self::isValid($value)) {
            return $default;
        }

        return intval($value);
    }

}
