<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use Composer\Semver\Constraint\ConstraintInterface;
use Composer\Semver\VersionParser;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\CommandConfigurator;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * @extends Fact<ConstraintInterface>
 */
final class PhpConstraint extends Fact implements CommandConfigurator
{
    private const string DEFAULT_OPTION = 'php-constraint-default';

    public static function configureCommand(Command $command): void
    {
        $command->addOption(self::DEFAULT_OPTION, mode: InputOption::VALUE_REQUIRED, default: '^8.3');
    }

    public static function resolve(Facts $facts, Cli $cli): ConstraintInterface
    {
        $composerJson = $facts[ComposerJson::class];

        if (isset($composerJson['require']['php'])) {
            try {
                return self::normalize($composerJson['require']['php']);
            } catch (\InvalidArgumentException) {
            }
        }

        $default = $cli->getOption(self::DEFAULT_OPTION);
        \assert(\is_string($default));

        return $cli->ask(
            question: 'PHP constraint',
            default: $default,
            normalizer: self::normalize(...),
        );
    }

    private static function normalize(string $constraint): ConstraintInterface
    {
        /** @var VersionParser */
        static $parser = new VersionParser();

        try {
            return $parser->parseConstraints($constraint);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }
    }
}
