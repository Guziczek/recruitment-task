<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Input\File;

use Bajcik\IO\File;
use ThirdBridge\Processor\Input\Input;

abstract class FileInput implements Input
{

    /**
     * @var File
     */
    private $file;

    /**
     * FileInput constructor.
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

}