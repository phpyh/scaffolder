<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\PackageType;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum Changelog implements Change
{
    case Change;
    private const string FILE = 'CHANGELOG.md';

    public function decide(Facts $facts, Project $project): ?callable
    {
        if ($facts[PackageType::class] === PackageType::PROJECT || $project->exists(self::FILE)) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            \sprintf('Write `%s`...', self::FILE),
            static fn() => $project->copy(__DIR__ . '/../../files/' . self::FILE, self::FILE),
        );
    }
}
