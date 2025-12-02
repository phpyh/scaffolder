<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder;

use PHPyh\Scaffolder\Change\GitHubWorkflow;
use PHPyh\Scaffolder\Change\RemoveFile;
use Symfony\Component\Console\SingleCommandApplication;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new SingleCommandApplication();

Fact\Project::configureCommand($app);
Fact\PackageType::configureCommand($app);
Fact\PackageVendor::configureCommand($app);
Fact\PhpConstraint::configureCommand($app);
Fact\Authors::configureCommand($app);
Fact\License::configureCommand($app);
Fact\UserName::configureCommand($app);
Fact\UserEmail::configureCommand($app);

$app
    ->setCode(new Command([
        new Change\CopyFile(__DIR__ . '/../files/Makefile', 'Makefile'),
        Change\Readme::Change,
        new Change\CopyFile(__DIR__ . '/../files/compose.yaml', '.devcontainer/compose.yaml'),
        new Change\CopyFile(__DIR__ . '/../files/devcontainer.json', '.devcontainer/devcontainer.json'),
        Change\Env::Change,
        Change\Src::Change,
        Change\Tests::Change,
        Change\License::Change,
        new Change\CopyFile(__DIR__ . '/../files/.gitattributes', '.gitattributes'),
        new Change\CopyFile(__DIR__ . '/../files/.gitignore', '.gitignore'),
        new Change\CopyFile(__DIR__ . '/../files/.php-cs-fixer.dist.php', '.php-cs-fixer.dist.php'),
        new Change\CopyFileIfNotExists(__DIR__ . '/../files/CHANGELOG.md', 'CHANGELOG.md'),
        new Change\CopyFile(__DIR__ . '/../files/phpstan.dist.neon', 'phpstan.dist.neon'),
        new Change\CopyFile(__DIR__ . '/../files/phpunit.xml.dist', 'phpunit.xml.dist'),
        new Change\CopyFile(__DIR__ . '/../files/rector.php', 'rector.php'),
        new RemoveFile('.github/workflows/check.yml'),
        GitHubWorkflow::Change,
        new Change\CopyFile(__DIR__ . '/../files/infection.json5.dist', 'infection.json5.dist'),
        Change\ComposerJson::Change,
        Change\PHPUnit::Change,
    ]))
    ->run();
