<?php
declare(strict_types = 1);

namespace ThirdBridge\Domain;

class User
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var UserValue
     */
    private $value;

    /**
     * @param string $name
     * @param bool $active
     * @param UserValue $value
     */
    public function __construct(string $name, bool $active, UserValue $value)
    {
        $this->name = $name;
        $this->active = $active;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return UserValue
     */
    public function getValue(): UserValue
    {
        return $this->value;
    }

}