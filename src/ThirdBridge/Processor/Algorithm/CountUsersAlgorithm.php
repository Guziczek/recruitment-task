<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Algorithm;

/**
 * Algorithm to count users.
 */
class CountUsersAlgorithm implements Algorithm
{

    public function reduce($userListIterable)
    {
        $userCount = 0;

        foreach ($userListIterable as $value) {
            $userCount++;
        }

        return $userCount;
    }

}
