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
final class PackageVendor extends Fact implements CommandConfigurator
{
    private const string DEFAULT_OPTION = 'vendor-default';

    public static function configureCommand(Command $command): void
    {
        $command->addOption(self::DEFAULT_OPTION, mode: InputOption::VALUE_REQUIRED);
    }

    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        $composerJson = $facts[ComposerJson::class];

        $value = explode('/', $composerJson['name'] ?? '')[0];

        if (self::isValid($value)) {
            return $value;
        }

        $default = $cli->getOption(self::DEFAULT_OPTION);
        \assert($default === null || \is_string($default));

        return $cli->ask(
            question: 'Package vendor name (`thesis` in `thesis/amqp`)',
            normalizer: static fn(string $input): ?string => self::isValid($input) ? $input : null,
            default: $default,
        );
    }

    /**
     * @phpstan-assert-if-true non-empty-string $vendor
     */
    private static function isValid(mixed $vendor): bool
    {
        return \is_string($vendor) && preg_match('~^[a-z0-9]([_.-]?[a-z0-9]+)*$~D', $vendor) === 1;
    }
}
