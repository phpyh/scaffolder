<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Examples;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum PHPStan implements Change
{
    case Change;
    private const string FILE = 'phpstan.dist.neon';

    public function decide(Facts $facts, Project $project): ?callable
    {
        $contents = $project->read(__DIR__ . '/../../files/' . self::FILE);

        if (!$facts[Examples::class]) {
            $contents = preg_replace("/.*examples.*\n/", '', $contents);
            \assert($contents !== null);
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
