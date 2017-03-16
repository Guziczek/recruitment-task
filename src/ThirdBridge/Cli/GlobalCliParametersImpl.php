<?php
declare(strict_types = 1);

namespace ThirdBridge\Cli;

class GlobalCliParametersImpl implements CliParameters
{

    public function hasParameter(string $name): bool
    {
        $paramMap = getopt("{$name}:");
        return isset($paramMap[$name]);
    }

    public function getParameter(string $name, string $default = ''): string
    {
        if (!$this->hasParameter($name)) {
            return $default;
        }

        $paramMap = getopt("{$name}:");
        return (string)$paramMap[$name];
    }

    public function hasLongParameter(string $name): bool
    {
        $paramMap = getopt('', ["{$name}:"]);
        return isset($paramMap[$name]);
    }

    public function getLongParameter(string $name, string $default = ''): string
    {
        if (!$this->hasLongParameter($name)) {
            return $default;
        }

        $paramMap = getopt('', ["{$name}:"]);
        return (string)$paramMap[$name];
    }

}