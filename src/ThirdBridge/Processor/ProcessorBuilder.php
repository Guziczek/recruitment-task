<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor;

use ThirdBridge\Processor\Algorithm\Algorithm;
use ThirdBridge\Processor\Filter\Filter;
use ThirdBridge\Processor\Input\Input;
use ThirdBridge\Processor\Output\Output;

class ProcessorBuilder
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

    public function setAlgorithm(Algorithm $algorithm): self
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    public function setInput(Input $input): self
    {
        $this->input = $input;
        return $this;
    }

    public function setOutput(Output $output): self
    {
        $this->output = $output;
        return $this;
    }

    public function addFilter(Filter $filter): self
    {
        $this->filterList[] = $filter;
        return $this;
    }

    public function build(): Processor
    {
        if (null == $this->algorithm) {
            throw new \InvalidArgumentException('Algorithm must be set.');
        }
        if (null == $this->input) {
            throw new \InvalidArgumentException('Input must be set.');
        }
        if (null == $this->output) {
            throw new \InvalidArgumentException('Output must be set.');
        }

        $processor = new Processor($this->algorithm, $this->input, $this->output);

        foreach ($this->filterList as $filter) {
            $processor->addFilter($filter);
        }

        return $processor;
    }

}