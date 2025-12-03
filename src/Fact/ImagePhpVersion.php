<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use Composer\Semver\Constraint\Constraint;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<value-of<self::VERSIONS>>
 */
final class ImagePhpVersion extends Fact
{
    private const array VERSIONS = [
        '8.2',
        '8.3',
        '8.4',
        '8.5',
    ];

    public static function resolve(Facts $facts, Cli $cli): string
    {
        $php = $facts[PhpConstraint::class];

        foreach (self::VERSIONS as $version) {
            if ($php->matches(new Constraint('==', $version . '.9999999'))) {
                return $version;
            }
        }

        throw new \RuntimeException(\sprintf('No `phpyh/php` image matches `%s` constraint', $php->getPrettyString()));
    }
}
