<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\ImagePhpVersion;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum Env implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        $contents = <<<MD
            # Put env variables defaults here
            # Override locally in gitignored .env.local
            PHP_IMAGE_VERSION={$facts[ImagePhpVersion::class]}
            
            MD;

        if ($project->tryRead('.env') === $contents) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            'Write `.env`...',
            static fn() => $project->write('.env', $contents),
        );
    }
}
