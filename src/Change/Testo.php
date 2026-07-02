<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Fact\UseTesto;
use PHPyh\Scaffolder\Facts;

enum Testo implements Change
{
    case Change;
    private const string FILE = 'testo.php';

    public function decide(Facts $facts, Project $project): ?callable
    {
        if (!$facts[UseTesto::class]) {
            return null;
        }

        $contents = $project->read(__DIR__ . '/../../files/' . self::FILE);

        if ($project->tryRead(self::FILE) === $contents) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            \sprintf('Write `%s`...', self::FILE),
            static function () use ($project, $contents): void {
                $project->write(self::FILE, $contents);
                $project->remove('phpunit.xml.dist');
                $project->remove('phpunit.xml');
            },
        );
    }
}
