<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<bool>
 */
final class Examples extends Fact
{
    public static function resolve(Facts $facts, Cli $cli): bool
    {
        if ($facts[Project::class]->exists('examples')) {
            return true;
        }

        if ($facts[PackageType::class] !== PackageType::LIBRARY) {
            return false;
        }

        return $cli->confirm('Add examples directory?');
    }
}
