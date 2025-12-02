<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * @extends Fact<non-empty-string>
 */
final class PackageProject extends Fact
{
    private const string PATTERN = '[a-z0-9](([_.]|-{1,2})?[a-z0-9]+)*';
    private const string DEFAULT_OPTION = 'package-project-default';

    public static function configureCommand(Command $command): void
    {
        $command->addOption(self::DEFAULT_OPTION, mode: InputOption::VALUE_REQUIRED);
    }

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
            default: self::normalizeDefault($cli->getOption(self::DEFAULT_OPTION)),
        );
    }

    /**
     * @phpstan-assert-if-true non-empty-string $project
     */
    private static function isValid(string $project): bool
    {
        return preg_match('~^' . self::PATTERN . '$~D', $project) === 1;
    }

    private static function normalizeDefault(mixed $default): ?string
    {
        if (\is_string($default) && preg_match('~' . self::PATTERN . '~', $default, $matches) === 1) {
            return $matches[0];
        }

        return null;
    }
}
