<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Filter;

use ThirdBridge\Domain\User;

/**
 * All filters used in {@link Processor} should implement this interface.
 *
 * When {@link Filter#isAccepted()} method returns <code>false</code> on {@link User} object, object is excluded from algorithm computation..
 */
interface Filter
{

    /**
     * @param User $user user to check
     * @return bool returns <code>true</code> if {@link User} can be processed by algorithm, <code>false</code> otherwise.
     */
    public function isAccepted(User $user): bool;

}