<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Algorithm;

use PHPUnit\Framework\TestCase;
use ThirdBridge\Domain\User;
use ThirdBridge\Domain\UserValue;

/**
 * @covers CountUsersAlgorithm
 */
class CountUsersAlgorithmTest extends TestCase
{

    public function testExpectZeroOnEmptyInput()
    {
        $sumAlgorithm = new CountUsersAlgorithm();
        $input = [];
        $this->assertSame(0, $sumAlgorithm->reduce($input));
    }

    public function testCalculateSum()
    {
        $sumAlgorithm = new CountUsersAlgorithm();
        $input = [
            new User('John', true, UserValue::of(100)),
            new User('Paul', false, UserValue::of(50)),
            new User('Mark', true, UserValue::of(-25)),
        ];
        $this->assertSame(3, $sumAlgorithm->reduce($input));
    }

}
