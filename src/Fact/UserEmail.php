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
 * @extends Fact<?non-empty-string>
 */
final class UserEmail extends Fact implements CommandConfigurator
{
    private const string DEFAULT_OPTION = 'user-email-default';
    private const string NULL_ANSWER = 'n';

    public static function configureCommand(Command $command): void
    {
        $command->addOption(self::DEFAULT_OPTION, mode: InputOption::VALUE_REQUIRED);
    }

    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        $default = $cli->getOption(self::DEFAULT_OPTION);
        \assert($default === null || \is_string($default));

        $answer = $cli->ask(
            question: 'Your email (n to skip)',
            normalizer: static fn(string $input) => self::isValid($input) ? $input : null,
            default: $default,
        );

        if ($answer === self::NULL_ANSWER) {
            return null;
        }

        return $answer;
    }

    /**
     * @phpstan-assert-if-true non-empty-string $email
     */
    private static function isValid(string $email): bool
    {
        return $email === self::NULL_ANSWER
            || filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
