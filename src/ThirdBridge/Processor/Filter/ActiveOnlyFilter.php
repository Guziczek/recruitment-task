<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Filter;

use ThirdBridge\Domain\User;

/**
 * Filters only active users.
 */
class ActiveOnlyFilter implements Filter
{

    public function isAccepted(User $user): bool
    {
        return $user->isActive();
    }

}