<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Input;

use ThirdBridge\Domain\User;

interface Input
{

    /**
     * @return User[]
     */
    public function getIterableUsers();

}