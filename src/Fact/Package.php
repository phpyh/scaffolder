<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<non-empty-string>
 */
final class Package extends Fact
{
    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        return $facts[PackageVendor::class] . '/' . $facts[PackageProject::class];
    }
}
