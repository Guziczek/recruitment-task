<?php
declare(strict_types = 1);

namespace ThirdBridge\Cli;

class CustomCliParametersImpl implements CliParameters
{
    /**
     * @var string[]
     */
    private $parameterMap = [];

    /**
     * @var string[]
     */
    private $longParameterMap = [];

    public function __construct(array $parameterMap = [], array $longParameterMap = [])
    {
        $this->parameterMap = $parameterMap;
        $this->longParameterMap = $longParameterMap;
    }

    public function hasParameter(string $name): bool
    {
        return isset($this->parameterMap[$name]);
    }

    public function getParameter(string $name, string $default = ''): string
    {
        if (!$this->hasParameter($name)) {
            return $default;
        }

        return (string)$this->parameterMap[$name];
    }

    public function hasLongParameter(string $name): bool
    {
        return isset($this->longParameterMap[$name]);
    }

    public function getLongParameter(string $name, string $default = ''): string
    {
        if (!$this->hasLongParameter($name)) {
            return $default;
        }

        return (string)$this->longParameterMap[$name];
    }
}