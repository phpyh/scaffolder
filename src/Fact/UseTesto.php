<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<bool>
 */
final class UseTesto extends Fact
{
    public static function resolve(Facts $facts, Cli $cli): bool
    {
        if (!$facts[Project::class]->exists('phpunit.xml.dist')) {
            return true;
        }

        return $cli->confirm('Replace PHPUnit with Testo?');
    }
}
