<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Output;

/**
 * Outputs to stdout.
 */
class ConsoleOutput implements Output
{

    public function write(string $output)
    {
        echo $output;
    }

}