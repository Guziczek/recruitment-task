<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor;

use ThirdBridge\Domain\User;
use ThirdBridge\Processor\Algorithm\Algorithm;
use ThirdBridge\Processor\Filter\Filter;
use ThirdBridge\Processor\Input\Input;
use ThirdBridge\Processor\Output\Output;

/**
 * Class apply specified algorithm to the list of {@link User} objects.
 *
 * Source of objects could be any class implementing {@link Input} interface. Algorithm result is redirected to specified {@link Output}.
 *
 * You can add any number {@link Filter} to narrow which objects are processed by algorithm.
 *
 * Records are processed one by one using generators, there is no need to load all data set at once, saving memory and allows processing of huge data sets (depends on {@link Input} implementation).
 */
class Processor
{

    /**
     * @var Algorithm
     */
    private $algorithm;
    /**
     * @var Input
     */
    private $input;
    /**
     * @var Output
     */
    private $output;

    /**
     * @var Filter[]
     */
    private $filterList = [];

    /**
     * @param Algorithm $algorithm
     * @param Input $input
     * @param Output $output
     */
    public function __construct(Algorithm $algorithm, Input $input, Output $output)
    {
        $this->algorithm = $algorithm;
        $this->input = $input;
        $this->output = $output;
    }

    public function execute()
    {
        $filteredElements = $this->createFilteredElementsIterator();

        $outputValue = $this->algorithm->reduce($filteredElements);

        $this->output->write((string)$outputValue);
    }

    public function addFilter(Filter $filter)
    {
        $this->filterList[] = $filter;
    }

    private function createFilteredElementsIterator(): \Generator
    {
        foreach ($this->input->getIterableUsers() as $element) {
            if (!$this->isAcceptedByFilters($element)) {
                continue;
            }

            yield $element;
        }
    }

    private function isAcceptedByFilters(User $element): bool
    {
        foreach ($this->filterList as $filter) {
            if (!$filter->isAccepted($element)) {
                return false;
            }
        }

        return true;
    }

}