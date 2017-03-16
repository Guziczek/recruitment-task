<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$algorithm = new \ThirdBridge\Processor\Algorithm\SumValuesAlgorithm();
$cliParameters = \ThirdBridge\Cli\CliParametersFactory::createFromCurrentScript();

$processorScript = new \ThirdBridge\ProcessorScript($algorithm, $cliParameters);
$exitCode = $processorScript->execute();

exit($exitCode);
