<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Filter;

use PHPUnit\Framework\TestCase;
use ThirdBridge\Domain\User;
use ThirdBridge\Domain\UserValue;

/**
 * @covers ActiveOnlyFilterTest
 */
class ActiveOnlyFilterTest extends TestCase
{

    public function testAcceptActiveUser()
    {
        $activeOnlyFilter = new ActiveOnlyFilter();
        $user = new User('John', true, UserValue::of(100));

        $this->assertSame(true, $activeOnlyFilter->isAccepted($user));
    }

    public function testDeclineInactiveUser()
    {
        $activeOnlyFilter = new ActiveOnlyFilter();
        $user = new User('John', false, UserValue::of(100));

        $this->assertSame(false, $activeOnlyFilter->isAccepted($user));
    }

}
