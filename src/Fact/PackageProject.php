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

        if (isset($composerJson['name'])) {
            try {
                return self::normalize(explode('/', $composerJson['name'])[1] ?? '');
            } catch (\InvalidArgumentException) {
            }
        }

        return $cli->ask(
            question: 'Package project name (`amqp` in `thesis/amqp`)',
            default: self::normalizeDefault($cli->getOption(self::DEFAULT_OPTION)),
            normalizer: self::normalize(...),
        );
    }

    private static function normalizeDefault(mixed $default): ?string
    {
        if (\is_string($default) && preg_match('~' . self::PATTERN . '~', $default, $matches) === 1) {
            return $matches[0];
        }

        return null;
    }

    /**
     * @return non-empty-string
     */
    private static function normalize(string $project): string
    {
        if (preg_match('~^' . self::PATTERN . '$~D', $project) === 1) {
            return $project; // @phpstan-ignore return.type
        }

        throw new \InvalidArgumentException('Invalid project name');
    }
}
