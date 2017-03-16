<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Output;

use Bajcik\IO\File;

/**
 * Outputs to file.
 */
class FileOutput implements Output
{

    /**
     * @var File
     */
    private $file;

    /**
     * FileOutput constructor.
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function write(string $output)
    {
        $this->file->getDirectory()->makeDirs();
        $this->file->setContents($output);
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

}
