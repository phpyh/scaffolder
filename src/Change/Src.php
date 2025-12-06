<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Namespace_;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum Src implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        if ($project->directoryHasAnyFiles('src')) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            'Write `src/index.php`...',
            static fn() => $project->write(
                'src/index.php',
                <<<PHP
                    <?php

                    declare(strict_types=1);

                    namespace {$facts[Namespace_::class]};

                    echo 'Hello world!', PHP_EOL;
                    PHP,
            ),
        );
    }
}
