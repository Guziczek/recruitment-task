<?php
declare(strict_types = 1);

namespace ThirdBridge\Processor\Input;

use Bajcik\IO\File;
use Bajcik\Util\StringUtils;

class InputFactory
{

    /**
     * @param File $file
     * @throws \InvalidArgumentException if file extension not supported
     * @return Input
     */
    public function createForFile(File $file): Input
    {
        $extension = $file->getExtension();

        $inputClass = __NAMESPACE__ . '\\File\\' . StringUtils::camelizeUpper($extension) . 'FileInput';
        if (!\class_exists($inputClass)) {
            throw new \InvalidArgumentException("Given file extension not supported: {$extension}.");
        }

        return new $inputClass($file);
    }

}