<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\PhpImageVersions;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum Compose implements Change
{
    case Change;
    private const string FILE = 'compose.yaml';

    public function decide(Facts $facts, Project $project): ?callable
    {
        $contents = $project->read(__DIR__ . '/../../files/' . self::FILE);

        $contents = str_replace(
            '${PHP_VERSION:-8.5}',
            \sprintf('${PHP_VERSION:-%s}', $facts[PhpImageVersions::class][0]),
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
