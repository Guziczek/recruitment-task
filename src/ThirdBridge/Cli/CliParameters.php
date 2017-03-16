<?php
declare(strict_types = 1);

namespace ThirdBridge\Cli;

interface CliParameters
{

    public function hasParameter(string $name): bool;

    public function getParameter(string $name, string $default = ''): string;

    public function hasLongParameter(string $name): bool;

    public function getLongParameter(string $name, string $default = ''): string;

}