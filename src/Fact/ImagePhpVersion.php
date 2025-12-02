<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

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
        $lowerBound = $facts[PhpConstraint::class]->getLowerBound()->getVersion();

        foreach (self::VERSIONS as $version) {
            if (str_contains($lowerBound, $version)) {
                return $version;
            }
        }

        return $cli->askChoice(
            question: 'PHP Docker image version',
            choices: self::VERSIONS,
        );
    }
}
