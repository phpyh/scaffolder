<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder;

use PHPyh\Scaffolder\Fact\Project;

interface Change
{
    /**
     * @return ?callable(Cli): void
     */
    public function decide(Facts $facts, Project $project): ?callable;
}
