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
final class UserName extends Fact implements CommandConfigurator
{
    private const string DEFAULT_OPTION = 'user-name-default';

    public static function configureCommand(Command $command): void
    {
        $command->addOption(self::DEFAULT_OPTION, mode: InputOption::VALUE_REQUIRED);
    }

    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        $default = $cli->getOption(self::DEFAULT_OPTION);
        \assert($default === null || \is_string($default));

        return $cli->ask(
            question: 'Your name',
            default: $default,
            normalizer: static fn(string $input) => $input === ''
                ? throw new \InvalidArgumentException('Name must not be empty')
                : $input,
        );
    }
}
