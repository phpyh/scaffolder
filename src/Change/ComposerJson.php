<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use Composer\Semver\Semver;
use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\Authors;
use PHPyh\Scaffolder\Fact\ComposerJson as ComposerJsonFact;
use PHPyh\Scaffolder\Fact\License;
use PHPyh\Scaffolder\Fact\Namespace_;
use PHPyh\Scaffolder\Fact\Package;
use PHPyh\Scaffolder\Fact\PackageType;
use PHPyh\Scaffolder\Fact\PhpConstraint;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Fact\Title;
use PHPyh\Scaffolder\Facts;

enum ComposerJson implements Change
{
    case Change;
    private const int JSON_FLAGS = JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES;

    public function decide(Facts $facts, Project $project): ?callable
    {
        $new = $original = $facts[ComposerJsonFact::class];

        $new['name'] = $facts[Package::class];
        $new['description'] ??= $facts[Title::class];
        $new['type'] = $facts[PackageType::class];
        $new['authors'] ??= $facts[Authors::class];
        $new['require']['php'] ??= $facts[PhpConstraint::class]->getPrettyString();
        $new['config']['sort-packages'] = true;
        $new['license'] = $facts[License::class];

        if (!$project->exists('composer.json')) {
            $new['autoload']['psr-4'][$facts[Namespace_::class] . '\\'] = 'src/';
            $new['autoload-dev']['psr-4'][$facts[Namespace_::class] . '\\'] = 'tests/';
            $new['require-dev']['phpunit/phpunit'] = match (true) {
                Semver::satisfies('8.2.9999999', $new['require']['php']) => '^11.5',
                default => '^12.4',
            };
        }

        if ($new['type'] === PackageType::PROJECT) {
            unset($new['config']['lock']);
            $removeLock = false;
        } else {
            $new['config']['lock'] = false;
            $removeLock = $project->exists('composer.lock');
        }

        if ($new === $original && !$removeLock) {
            return null;
        }

        return static function (Cli $cli) use ($project, $new, $removeLock): void {
            $cli->step(
                'Write `composer.json`...',
                static fn() => $project->write('composer.json', json_encode($new, self::JSON_FLAGS)),
            );

            if ($removeLock) {
                $cli->step(
                    'Remove `composer.lock`...',
                    static fn() => $project->remove('composer.lock'),
                );
            }

            $cli->step(
                'Normalize `composer.json`...',
                static fn() => $project->execute('composer normalize --no-check-lock --no-update-lock'),
            );
        };
    }
}
