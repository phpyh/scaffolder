<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<bool>
 */
final class Bin extends Fact
{
    public static function resolve(Facts $facts, Cli $cli): bool
    {
        if ($facts[Project::class]->exists('bin')) {
            return true;
        }

        return $cli->confirm('Add `bin` directory?');
    }
}
