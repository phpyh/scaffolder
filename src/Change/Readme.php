<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Package;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Fact\Title;
use PHPyh\Scaffolder\Facts;

enum Readme implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        if ($project->exists('README.md')) {
            return null;
        }

        $contents = <<<MD
            # {$facts[Title::class]}
            
            ## Installation
            
            ```shell
            composer require {$facts[Package::class]}
            ```

            MD;

        return static fn(Cli $cli) => $cli->step(
            'Write `README.md`...',
            static fn() => $project->write('README.md', $contents),
        );
    }
}
