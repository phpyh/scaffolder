<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\ComposerJson as ComposerJsonFact;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum PHPUnit implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        $composerJson = $facts[ComposerJsonFact::class];

        if (isset($composerJson['require-dev']['phpunit/phpunit'])) {
            return null;
        }

        return static fn(Cli $cli) => $cli->step(
            'Require `phpunit/phpunit`...',
            static fn() => $project->execute('composer require --dev phpunit/phpunit'),
        );
    }
}
