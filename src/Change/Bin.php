<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\HasBin;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum Bin implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        if ($project->directoryHasAnyFiles('bin')) {
            return null;
        }

        if (!$facts[HasBin::class]) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            'Write `bin/.gitignore`...',
            static fn() => $project->write('bin/.gitignore'),
        );
    }
}
