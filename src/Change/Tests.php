<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum Tests implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        if ($project->directoryHasAnyFiles('tests')) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            'Write `tests/.gitignore`...',
            static fn() => $project->write('tests/.gitignore'),
        );
    }
}
