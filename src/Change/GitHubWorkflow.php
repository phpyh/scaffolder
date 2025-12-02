<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Fact\PackageType;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum GitHubWorkflow implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        return new CopyFile(
            origin: \sprintf(
                __DIR__ . '/../../files/%s_check.yaml',
                $facts[PackageType::class] === PackageType::LIBRARY ? 'library' : 'project',
            ),
            target: '.github/workflows/check.yaml',
        )->decide($facts, $project);
    }
}
