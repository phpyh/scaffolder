<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Fact\UseTesto;
use PHPyh\Scaffolder\Facts;

enum Makefile implements Change
{
    case Change;
    private const string FILE = 'Makefile';

    public function decide(Facts $facts, Project $project): ?callable
    {
        $contents = $project->read(__DIR__ . '/../../files/' . self::FILE);

        if ($facts[UseTesto::class]) {
            $contents = str_replace('phpunit', 'testo', $contents);
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
