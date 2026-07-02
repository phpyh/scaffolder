<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum RemoveLegacyEnvFile implements Change
{
    case Change;
    private const string FILE = '.env';
    private const string PATTERN = '/\A# Put env variables defaults here\n# Override locally in gitignored \.env\.local\nPHP_IMAGE_VERSION=[^\n]+\n\z/';

    public function decide(Facts $facts, Project $project): ?callable
    {
        $contents = $project->tryRead(self::FILE);

        if ($contents === null || preg_match(self::PATTERN, $contents) !== 1) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            \sprintf('Remove `%s`...', self::FILE),
            static fn() => $project->remove(self::FILE),
        );
    }
}
