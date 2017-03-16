<?php
declare(strict_types = 1);

namespace ThirdBridge;

use Bajcik\IO\File;
use Bajcik\Util\StringUtils;
use ThirdBridge\Cli\CliParameters;
use ThirdBridge\Processor\Algorithm\Algorithm;
use ThirdBridge\Processor\Filter\ActiveOnlyFilter;
use ThirdBridge\Processor\Input\Input;
use ThirdBridge\Processor\Input\InputFactory;
use ThirdBridge\Processor\Output\ConsoleOutput;
use ThirdBridge\Processor\Output\FileOutput;
use ThirdBridge\Processor\Output\Output;
use ThirdBridge\Processor\Processor;
use ThirdBridge\Processor\ProcessorBuilder;

/**
 * Executes {@link Processor} based on script CLI parameters.
 */
class ProcessorScript
{

    const EXIT_CODE_OK = 0;
    const EXIT_CODE_INVALID_SCRIPT_PARAMETERS = 1;
    const EXIT_CODE_OTHER_ERROR = 2;

    /**
     * @var Algorithm
     */
    private $algorithm;

    /**
     * @var CliParameters
     */
    private $cliParameters;

    public function __construct(Algorithm $algorithm, CliParameters $cliParameters)
    {
        $this->algorithm = $algorithm;
        $this->cliParameters = $cliParameters;
    }

    public function execute(): int
    {
        $exitCode = self::EXIT_CODE_OK;

        try {
            $this->validateParameters();

            $processor = $this->createProcessorFromCliParameters();
            $processor->execute();
        } catch (\InvalidArgumentException $e) {
            $exitCode = self::EXIT_CODE_INVALID_SCRIPT_PARAMETERS;

            $this->printError($e->getMessage());
            $this->printHelp();
        } catch (\Exception $e) {
            $exitCode = self::EXIT_CODE_OTHER_ERROR;

            $this->printError($e->getMessage());
        }

        return $exitCode;
    }

    private function validateParameters()
    {
        if (StringUtils::isEmpty($this->cliParameters->getLongParameter('input'))) {
            throw new \InvalidArgumentException('"--input" parameter value required');
        }

        if ($this->cliParameters->hasLongParameter('output') && StringUtils::isEmpty($this->cliParameters->getLongParameter('output'))) {
            throw new \InvalidArgumentException('"--output" parameter value required');
        }
    }

    private function createProcessorFromCliParameters(): Processor
    {
        $builder = new ProcessorBuilder();
        $processor = $builder
            ->setAlgorithm($this->algorithm)
            ->setInput($this->createInput())
            ->setOutput($this->createOutput())
            ->addFilter(new ActiveOnlyFilter())
            ->build();

        return $processor;
    }

    private function createInput(): Input
    {
        $file = File::of($this->cliParameters->getLongParameter('input'));
        $inputFactory = new InputFactory();
        return $inputFactory->createForFile($file);
    }

    private function createOutput(): Output
    {
        if ($this->cliParameters->hasLongParameter('output')) {
            $file = File::of($this->cliParameters->getLongParameter('output'));
            return new FileOutput($file);
        } else {
            return new ConsoleOutput();
        }
    }

    private function printError(string $message)
    {
        echo $message;
        echo PHP_EOL;
    }

    private function printHelp()
    {
        echo "
Usage:
  {$this->getScriptFile()->getName()}  --input=\"input_file_path\" [--output=\"output_file_path\"]
    
Options:
    --input        path to input file
    --output       path to output file, if parameter is not specified output will be redirected to standard output
";
    }

    private function getScriptFile(): File
    {
        return File::of($_SERVER["SCRIPT_FILENAME"]);
    }

}
