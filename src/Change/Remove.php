<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

final readonly class Remove implements Change
{
    public function __construct(
        private string $path,
    ) {}

    public function decide(Facts $facts, Project $project): ?callable
    {
        if (!$project->exists($this->path)) {
            return null;
        }

        return fn(Cli $cli) => $cli->step(
            \sprintf('Remove `%s`...', $this->path),
            fn() => $project->remove($this->path),
        );
    }
}
