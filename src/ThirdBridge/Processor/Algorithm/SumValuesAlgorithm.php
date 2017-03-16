<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Algorithm;

/**
 * Algorithm to summarize all user values.
 */
class SumValuesAlgorithm implements Algorithm
{

    public function reduce($userListIterable)
    {
        $sum = 0;

        foreach ($userListIterable as $value) {
            $sum += $value->getValue()->toInt();
        }

        return $sum;
    }

}
