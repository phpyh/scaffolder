<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<bool>
 */
final class HasDocs extends Fact
{
    public static function resolve(Facts $facts, Cli $cli): bool
    {
        if ($facts[Project::class]->exists('docs')) {
            return true;
        }

        if ($facts[IsRescaffolding::class]) {
            return false;
        }

        return $cli->confirm('Add `docs` directory?', default: false);
    }
}
