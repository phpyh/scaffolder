<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Examples as ExamplesFact;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum Examples implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        if ($project->directoryHasAnyFiles('examples')) {
            return null;
        }

        if (!$facts[ExamplesFact::class]) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            'Write `examples/.gitignore`...',
            static fn() => $project->write('examples/.gitignore'),
        );
    }
}
