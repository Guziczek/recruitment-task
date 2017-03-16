<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Algorithm;

use PHPUnit\Framework\TestCase;
use ThirdBridge\Domain\User;
use ThirdBridge\Domain\UserValue;

/**
 * @covers SumValuesAlgorithm
 */
class SumValuesAlgorithmTest extends TestCase
{

    public function testExpectZeroOnEmptyInput()
    {
        $sumAlgorithm = new SumValuesAlgorithm();
        $input = [];
        $this->assertSame(0, $sumAlgorithm->reduce($input));
    }

    public function testCalculateSum()
    {
        $sumAlgorithm = new SumValuesAlgorithm();
        $input = [
            new User('John', true, UserValue::of(100)),
            new User('Paul', false, UserValue::of(50)),
            new User('Mark', true, UserValue::of(-25)),
        ];
        $this->assertSame(125, $sumAlgorithm->reduce($input));
    }

}
