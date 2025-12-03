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

        return $cli->ask(
            question: 'Your email (n to skip)',
            default: $default,
            normalizer: self::normalize(...),
        );
    }

    /**
     * @return ?non-empty-string
     */
    private static function normalize(string $email): ?string
    {
        if ($email === self::NULL_ANSWER) {
            return null;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            return $email;  // @phpstan-ignore return.type
        }

        throw new \InvalidArgumentException('Invalid email');
    }
}
