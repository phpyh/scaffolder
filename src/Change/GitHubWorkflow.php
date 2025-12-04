<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\PackageType;
use PHPyh\Scaffolder\Fact\PhpImageVersions;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum GitHubWorkflow implements Change
{
    case Change;
    private const string FILE = '.github/workflows/check.yaml';

    public function decide(Facts $facts, Project $project): ?callable
    {
        $contents = $project->read(\sprintf(
            __DIR__ . '/../../files/%s_check.yaml',
            $facts[PackageType::class] === PackageType::LIBRARY ? 'library' : 'project',
        ));
        $contents = str_replace(
            "'%matrix%'",
            \sprintf('[ %s ]', implode(', ', $facts[PhpImageVersions::class])),
            $contents,
        );

        if ($project->tryRead(self::FILE) === $contents) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            \sprintf('Write `%s`...', self::FILE),
            static fn() => $project->write(self::FILE, $contents),
        );
    }
}
