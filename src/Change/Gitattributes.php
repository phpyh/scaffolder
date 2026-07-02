<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\HasBin;
use PHPyh\Scaffolder\Fact\HasDocs;
use PHPyh\Scaffolder\Fact\HasExamples;
use PHPyh\Scaffolder\Fact\PackageType;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum Gitattributes implements Change
{
    case Change;
    private const string FILE = '.gitattributes';

    public function decide(Facts $facts, Project $project): ?callable
    {
        if ($facts[PackageType::class] === PackageType::PROJECT) {
            if (!$project->exists(self::FILE)) {
                return null;
            }

            return static fn(Cli $cli) => $cli->step(
                \sprintf('Remove `%s`...', self::FILE),
                static fn() => $project->remove(self::FILE),
            );
        }

        $contents = $project->read(__DIR__ . '/../../files/' . self::FILE);

        if (!$facts[HasBin::class]) {
            $contents = preg_replace("~^/bin/.*\n~m", '', $contents);
            \assert($contents !== null);
        }

        if (!$facts[HasDocs::class]) {
            $contents = preg_replace("~^/docs/.*\n~m", '', $contents);
            \assert($contents !== null);
        }

        if (!$facts[HasExamples::class]) {
            $contents = preg_replace("~^/examples/.*\n~m", '', $contents);
            \assert($contents !== null);
        }

        if ($project->tryRead(self::FILE) === $contents) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            \sprintf('Write `%s`...', self::FILE),
            static fn() => $project->write(self::FILE, $contents),
        );
    }
}
