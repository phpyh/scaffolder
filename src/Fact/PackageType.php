<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\CommandConfigurator;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * @extends Fact<non-empty-string>
 */
final class PackageType extends Fact implements CommandConfigurator
{
    public const string LIBRARY = 'library';
    public const string PROJECT = 'project';
    private const string DEFAULT_OPTION = 'type-default';

    public static function configureCommand(Command $command): void
    {
        $command->addOption(self::DEFAULT_OPTION, mode: InputOption::VALUE_REQUIRED, default: self::LIBRARY);
    }

    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        $composerJson = $facts[ComposerJson::class];

        if (isset($composerJson['type']) && self::isValid($composerJson['type'])) {
            return $composerJson['type'];
        }

        $default = $cli->getOption(self::DEFAULT_OPTION);
        \assert(\is_string($default));

        return $cli->ask(
            question: 'Package type',
            normalizer: static fn(string $input): ?string => self::isValid($input) ? $input : null,
            default: $default,
        );
    }

    /**
     * @phpstan-assert-if-true non-empty-string $type
     */
    private static function isValid(string $type): bool
    {
        return preg_match('~^[a-z0-9-]+$~D', $type) === 1;
    }
}
