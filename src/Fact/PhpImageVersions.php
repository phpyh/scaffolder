<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use Composer\Semver\Constraint\Constraint;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<non-empty-list<value-of<self::VERSIONS>>>
 */
final class PhpImageVersions extends Fact
{
    private const array VERSIONS = [
        '8.2',
        '8.3',
        '8.4',
        '8.5',
    ];

    public static function resolve(Facts $facts, Cli $cli): array
    {
        $constraint = $facts[PhpConstraint::class];

        $versions = array_values(
            array_filter(
                self::VERSIONS,
                static fn(string $version) => $constraint->matches(new Constraint('==', $version . '.9999999')),
            ),
        );

        if ($versions === []) {
            throw new \RuntimeException(\sprintf('No `phpyh/php` image matches `%s` constraint', $constraint->getPrettyString()));
        }

        return $versions;
    }
}
