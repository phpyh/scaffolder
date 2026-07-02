<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<bool>
 */
final class IsRescaffolding extends Fact
{
    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        return $facts[Project::class]->exists('composer.json');
    }
}
