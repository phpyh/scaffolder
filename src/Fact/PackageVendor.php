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

        if (isset($composerJson['name'])) {
            try {
                return self::normalize(explode('/', $composerJson['name'])[0]);
            } catch (\InvalidArgumentException) {
            }
        }

        $default = $cli->getOption(self::DEFAULT_OPTION);
        \assert($default === null || \is_string($default));

        return $cli->ask(
            question: 'Package vendor name (`thesis` in `thesis/amqp`)',
            default: $default,
            normalizer: self::normalize(...),
        );
    }

    /**
     * @return non-empty-string
     */
    private static function normalize(string $vendor): string
    {
        if (preg_match('~^[a-z0-9]([_.-]?[a-z0-9]+)*$~D', $vendor) === 1) {
            return $vendor; // @phpstan-ignore return.type
        }

        throw new \InvalidArgumentException('Invalid vendor');
    }
}
