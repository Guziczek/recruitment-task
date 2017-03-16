<?php
declare(strict_types = 1);

namespace ThirdBridge\Domain;

use Bajcik\Math\Integer;

/**
 *
 * User's "value" property. Value object.
 *
 * Requirements aren't clear what type should "value" be.
 * I've decided to create separate value object for this, in case it could change to anything else than integer.
 *
 */
class UserValue
{

    /**
     * @var int
     */
    private $value;

    /**
     * Creates object out of integer value.
     *
     * @param int $value
     * @return self
     */
    public static function of(int $value): self
    {
        return new self($value);
    }

    /**
     * Creates object out of string.
     *
     * @param string $value
     * @throws \InvalidArgumentException
     * @return self
     */
    public static function ofString(string $value): self
    {
        if (!Integer::isValid($value)) {
            throw new \InvalidArgumentException("Cannot convert given string to integer value: {$value}");
        }

        return new self((int)$value);
    }

    /**
     * ElementValue constructor.
     * @param int $value
     */
    private function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string)$this->value;
    }

}