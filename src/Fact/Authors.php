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
 * @phpstan-import-type Author from ComposerJson
 * @extends Fact<list<Author>>
 */
final class Authors extends Fact implements CommandConfigurator
{
    private const string OPTION = 'authors';

    public static function configureCommand(Command $command): void
    {
        $command->addOption(self::OPTION, mode: InputOption::VALUE_REQUIRED);
    }

    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        $composerJson = $facts[ComposerJson::class];

        if (isset($composerJson['authors'])) {
            return $composerJson['authors'];
        }

        $option = $cli->getOption(self::OPTION);

        if (\is_string($option)) {
            /** @var list<Author> */
            return json_decode($option, associative: true, flags: JSON_THROW_ON_ERROR);
        }

        $author = [
            'name' => $facts[UserName::class],
        ];

        $email = $facts[UserEmail::class];

        if ($email !== null) {
            $author['email'] = $email;
        }

        return [$author];
    }
}
