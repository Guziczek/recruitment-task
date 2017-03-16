<?php
declare(strict_types = 1);

namespace ThirdBridge;

use PHPUnit\Framework\TestCase;
use ThirdBridge\Cli\CliParametersFactory;
use ThirdBridge\Processor\Algorithm\SumValuesAlgorithm;

/**
 * @covers ProcessorScript
 */
class ProcessorScriptTest extends TestCase
{

    /**
     * @dataProvider dataFilesProvider
     * @param string $filePath
     * @param int $expectedSum
     */
    public function testDataFilesOutputToConsole(string $filePath, int $expectedSum)
    {
        $ps = new ProcessorScript(new SumValuesAlgorithm(), CliParametersFactory::createFromCustom([], ['input' => $filePath]));
        $exitCode = $ps->execute();

        $this->expectOutputString((string)$expectedSum);
        $this->assertSame(ProcessorScript::EXIT_CODE_OK, $exitCode);
    }

    public function dataFilesProvider()
    {
        // TODO paths & separate test data
        return [
            'csv file'  => [__DIR__ . '/../../data/file.csv', 900],
            'xml file'  => [__DIR__ . '/../../data/file.xml', 900],
            'yaml file' => [__DIR__ . '/../../data/file.yml', 900],
        ];
    }

}
