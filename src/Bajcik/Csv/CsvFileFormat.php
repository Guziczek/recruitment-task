<?php
declare(strict_types = 1);

namespace Bajcik\Csv;

/**
 * Info about CSV file format.
 * Value object.
 *
 * @author Paweł Cichorowski <pawel.cichorowski@gmail.com>
 *
 */
class CsvFileFormat
{

    /**
     *
     * @var string
     */
    private $delimeter;
    /**
     *
     * @var string
     */
    private $enclosure;
    /**
     *
     * @var string
     */
    private $encoding;

    /**
     *
     * @param string $delimeter
     * @param string $enclosure
     * @param string $encoding
     *
     * @return self
     */
    public static function of(string $delimeter, string $enclosure, string $encoding = 'UTF-8'): self
    {
        return new self($delimeter, $enclosure, $encoding);
    }

    /**
     *
     * @return self
     */
    public static function createExcelFormat(): self
    {
        return new self(';', '"', 'Windows-1250');
    }

    /**
     *
     * @param string $delimeter
     * @param string $enclosure
     * @param string $encoding
     */
    private function __construct(string $delimeter, string $enclosure, string $encoding)
    {
        $this->delimeter = $delimeter;
        $this->enclosure = $enclosure;
        $this->encoding = $encoding;
    }

    /**
     *
     * @return string
     */
    public function getDelimeter(): string
    {
        return $this->delimeter;
    }

    /**
     *
     * @return string
     */
    public function getEnclosure(): string
    {
        return $this->enclosure;
    }

    /**
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

}
