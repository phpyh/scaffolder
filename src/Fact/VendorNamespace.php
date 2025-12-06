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
 * @extends Fact<string>
 */
final class VendorNamespace extends Fact implements CommandConfigurator
{
    private const string DEFAULT_OPTION = 'vendor-namespace-default';

    public static function configureCommand(Command $command): void
    {
        $command->addOption(self::DEFAULT_OPTION, mode: InputOption::VALUE_REQUIRED);
    }

    public static function resolve(Facts $facts, Cli $cli): string
    {
        $option = $cli->getOption(self::DEFAULT_OPTION);

        if ($option !== null) {
            \assert(\is_string($option));

            try {
                return Namespace_::normalize($option);
            } catch (\InvalidArgumentException) {
            }
        }

        return Namespace_::pascalize($facts[PackageVendor::class]);
    }
}
