<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\HasBin;
use PHPyh\Scaffolder\Fact\HasExamples;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Fact\UseTesto;
use PHPyh\Scaffolder\Facts;

enum PHPStan implements Change
{
    case Change;
    private const string FILE = 'phpstan.dist.neon';

    public function decide(Facts $facts, Project $project): ?callable
    {
        $contents = $project->read(__DIR__ . '/../../files/' . self::FILE);

        if (!$facts[HasBin::class]) {
            $contents = preg_replace("/.*bin.*\n/", '', $contents);
            \assert($contents !== null);
        }

        if (!$facts[HasExamples::class]) {
            $contents = preg_replace("/.*examples.*\n/", '', $contents);
            \assert($contents !== null);
        }

        if ($facts[UseTesto::class]) {
            $contents = str_replace(
                <<<'TEXT'
                        - /composer/vendor/phpstan/phpstan-phpunit/extension.neon
                        - /composer/vendor/phpstan/phpstan-phpunit/rules.neon

                    TEXT,
                '',
                $contents,
            );
        }

        if ($project->tryRead(self::FILE) === $contents) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            \sprintf('Write `%s`...', self::FILE),
            static fn() => $project->write(self::FILE, $contents),
        );
    }
}
