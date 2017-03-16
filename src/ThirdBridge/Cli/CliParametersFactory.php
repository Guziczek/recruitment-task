<?php
declare(strict_types = 1);

namespace ThirdBridge\Cli;

class CliParametersFactory
{

    /**
     * Creates object from current running script CLI parameters.
     * @return CliParameters
     */
    public static function createFromCurrentScript(): CliParameters
    {
        return new GlobalCliParametersImpl();
    }

    /**
     * Creates object from custom parameters.
     *
     * @param array $parameterMap
     * @param array $longParameterMap
     * @return CliParameters
     */
    public static function createFromCustom(array $parameterMap, array $longParameterMap): CliParameters
    {
        return new CustomCliParametersImpl($parameterMap, $longParameterMap);
    }

}