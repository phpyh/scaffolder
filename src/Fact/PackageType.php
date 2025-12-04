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
    private const string DEFAULT_OPTION = 'package-type-default';

    public static function configureCommand(Command $command): void
    {
        $command->addOption(self::DEFAULT_OPTION, mode: InputOption::VALUE_REQUIRED, default: self::LIBRARY);
    }

    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        $composerJson = $facts[ComposerJson::class];

        if (isset($composerJson['type'])) {
            return $composerJson['type'];
        }

        $default = $cli->getOption(self::DEFAULT_OPTION);
        \assert(\is_string($default));

        return $cli->ask(
            question: 'Package type, e.g. project or library',
            default: $default,
            normalizer: self::normalize(...),
        );
    }

    /**
     * @return non-empty-string
     */
    private static function normalize(string $type): string
    {
        if (preg_match('~^[a-z0-9-]+$~D', $type) === 1) {
            return $type; // @phpstan-ignore return.type
        }

        throw new \InvalidArgumentException('Invalid package type');
    }
}
