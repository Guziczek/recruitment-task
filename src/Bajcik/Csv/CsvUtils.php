<?php
declare(strict_types = 1);

namespace Bajcik\Csv;

use Bajcik\IO\File;
use Bajcik\Util\StringUtils;

/**
 *
 * @author Paweł Cichorowski <pawel.cichorowski@gmail.com>
 *
 */
class CsvUtils
{

    /**
     * @param File $file
     * @param CsvFileFormat $csvFileFormat
     * @param bool $skipEmptyLines
     * @param int $skipFirstLinesCount
     * @return array[]
     */
    public static function createLinesGeneratorFromFile(File $file, CsvFileFormat $csvFileFormat, bool $skipEmptyLines = true, int $skipFirstLinesCount = 0)
    {
        $outputEncoding = 'UTF-8';

        $i = -1;
        foreach ($file->getLinesIterator() as $line) {
            $i++;
            if ($skipEmptyLines && StringUtils::isEmpty($line)) {
                continue;
            }
            if ($i < $skipFirstLinesCount) {
                continue;
            }
            $line = iconv($csvFileFormat->getEncoding(), $outputEncoding, $line);
            $lineAsArray = str_getcsv($line, $csvFileFormat->getDelimeter(), $csvFileFormat->getEnclosure());
            yield $lineAsArray;
        }
    }

}
