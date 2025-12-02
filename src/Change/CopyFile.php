<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

final readonly class CopyFile implements Change
{
    public function __construct(
        private string $origin,
        private string $target,
    ) {}

    public function decide(Facts $facts, Project $project): ?callable
    {
        $contents = $project->read($this->origin);

        if ($project->tryRead($this->target) === $contents) {
            return null;
        }

        return fn(Cli $cli) => $cli->step(
            \sprintf('Write `%s`...', $this->target),
            fn() => $project->write($this->target, $contents),
        );
    }
}
