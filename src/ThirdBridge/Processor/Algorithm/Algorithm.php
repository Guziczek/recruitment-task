<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Algorithm;

use ThirdBridge\Domain\User;

/**
 * All algorithms used in {@link Processor} should implement this interface.
 *
 * Algorithm performs "reduce" operation on {@link User} objects.
 */
interface Algorithm
{

    /**
     * Performs reduce operation on given {@link User} objects.
     *
     * @param User[] $userListIterable iterable list of {@link User} objects
     * @return mixed computed value of this algorithm
     */
    public function reduce($userListIterable);

}