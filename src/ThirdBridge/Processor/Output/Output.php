<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Output;

use Bajcik\IO\IOException;

interface Output
{
    /**
     * @param string $output
     * @throws IOException
     * @return mixed
     */
    public function write(string $output);
}
