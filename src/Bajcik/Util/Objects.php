<?php
declare(strict_types = 1);

namespace Bajcik\Util;

use Bajcik\Exception\NonBooleanException;
use Bajcik\Exception\NonIntegerException;
use Bajcik\Exception\NonStringException;
use Bajcik\Exception\NullPointerException;
use Bajcik\Math\Integer;

/**
 *
 * @author Paweł Cichorowski <pawel.cichorowski@gmail.com>
 *
 */
class Objects
{

    /**
     * Checks that the specified value is not null and throws a customized NullPointerException if it is.
     *
     * @param mixed $value
     *            the value to check for nullity
     * @param string $message
     *            detail message to be used in the event that a NullPointerException is thrown
     *
     * @throws NullPointerException if value is null
     * @return mixed
     */
    public static function requireNonNull($value, $message = null)
    {
        if (null === $value) {
            throw new NullPointerException($message);
        }

        return $value;
    }

    /**
     *
     * @param mixed $value
     * @param string $message
     *
     * @throws NonIntegerException
     * @return integer
     */
    public static function requireInteger($value, $message = null)
    {
        if ((null === $value) || !Integer::isValid($value)) {
            throw new NonIntegerException($message);
        }

        return intval($value);
    }

    /**
     *
     * @param mixed|null $value
     * @param string $message
     *
     * @throws NonIntegerException
     * @return integer|null
     */
    public static function requireIntegerOrNull($value, $message = null)
    {
        if (null === $value) {
            return null;
        }

        if (!Integer::isValid($value)) {
            throw new NonIntegerException($message);
        }

        return intval($value);
    }

    /**
     *
     * @param mixed $value
     * @param string $message
     *
     * @throws NonBooleanException
     * @return integer
     */
    public static function requireBoolean($value, $message = null)
    {
        if ((null === $value) || !BooleanUtils::isValid($value)) {
            throw new NonBooleanException($message);
        }

        return BooleanUtils::isTrue($value);
    }

    /**
     *
     * @param mixed $value
     * @param string $message
     *
     * @throws NonBooleanException
     * @return integer
     */
    public static function requireBooleanOrNull($value, $message = null)
    {
        if (null === $value) {
            return null;
        }
        if (!BooleanUtils::isValid($value)) {
            throw new NonBooleanException($message);
        }

        return BooleanUtils::isTrue($value);
    }

    /**
     *
     * @param mixed $value
     * @param string $message
     *
     * @throws NonIntegerException
     * @return string
     */
    public static function requireString($value, $message = null)
    {
        if (!is_string($value)) {
            throw new NonStringException($message);
        }

        return $value;
    }

    /**
     *
     * @param mixed $sourceObject
     * @param mixed $destinationObject
     */
    public static function copyPublicProperties($sourceObject, $destinationObject)
    {
        Objects::requireNonNull($sourceObject);
        Objects::requireNonNull($destinationObject);

        foreach ($sourceObject as $k => $v) {
            $destinationObject->{$k} = $v;
        }
    }

}
