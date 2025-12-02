<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<non-empty-string>
 */
final class PackageProject extends Fact
{
    private const string PATTERN = '[a-z0-9](([_.]|-{1,2})?[a-z0-9]+)*';

    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        $composerJson = $facts[ComposerJson::class];

        $project = explode('/', $composerJson['name'] ?? '')[1] ?? '';

        if (self::isValid($project)) {
            return $project;
        }

        return $cli->ask(
            question: 'Package project name (`amqp` in `thesis/amqp`)',
            normalizer: static fn(string $input): ?string => self::isValid($input) ? $input : null,
            default: self::default($facts),
        );
    }

    private static function default(Facts $facts): ?string
    {
        if (preg_match('~' . self::PATTERN . '~', $facts[Project::class]->basename, $matches) === 1) {
            return $matches[0];
        }

        return null;
    }

    /**
     * @phpstan-assert-if-true non-empty-string $project
     */
    private static function isValid(string $project): bool
    {
        return preg_match('~^' . self::PATTERN . '$~D', $project) === 1;
    }
}
