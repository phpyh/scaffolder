<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<non-empty-string>
 */
final class Title extends Fact
{
    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        $description = preg_replace_callback(
            '~[-_./]+(\w)~',
            static fn(array $matches) => ' ' . strtoupper($matches[1]),
            $facts[Package::class],
        );
        \assert($description !== null && $description !== '');

        return ucfirst($description);
    }
}
