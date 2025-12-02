<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

final readonly class RemoveFile implements Change
{
    public function __construct(
        private string $file,
    ) {}

    public function decide(Facts $facts, Project $project): ?callable
    {
        if (!$project->exists($this->file)) {
            return null;
        }

        return fn(Cli $cli) => $cli->step(
            \sprintf('Remove `%s`...', $this->file),
            fn() => $project->remove($this->file),
        );
    }
}
