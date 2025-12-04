<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum Src implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        if ($project->directoryHasAnyFiles('src')) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            'Write `src/.gitignore`...',
            static fn() => $project->write('src/.gitignore'),
        );
    }
}
