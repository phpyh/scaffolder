<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder;

use PHPyh\Scaffolder\Change\Examples;
use PHPyh\Scaffolder\Change\GitHubWorkflow;
use PHPyh\Scaffolder\Change\PHPCSFixer;
use PHPyh\Scaffolder\Change\PHPStan;
use PHPyh\Scaffolder\Change\Rector;
use PHPyh\Scaffolder\Change\RemoveFile;
use Symfony\Component\Console\SingleCommandApplication;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new SingleCommandApplication();

Fact\Project::configureCommand($app);
Fact\PackageType::configureCommand($app);
Fact\PackageVendor::configureCommand($app);
Fact\PackageProject::configureCommand($app);
Fact\PhpConstraint::configureCommand($app);
Fact\Authors::configureCommand($app);
Fact\License::configureCommand($app);
Fact\UserName::configureCommand($app);
Fact\UserEmail::configureCommand($app);

$app
    ->setCode(new Command([
        Change\ComposerJson::Change,
        new Change\CopyFile(__DIR__ . '/../files/.devcontainer.json', '.devcontainer.json'),
        new Change\CopyFile(__DIR__ . '/../files/.gitattributes', '.gitattributes'),
        new Change\CopyFile(__DIR__ . '/../files/.gitignore', '.gitignore'),
        new Change\CopyFile(__DIR__ . '/../files/compose.yaml', 'compose.yaml'),
        new Change\CopyFile(__DIR__ . '/../files/infection.json5.dist', 'infection.json5.dist'),
        new Change\CopyFile(__DIR__ . '/../files/Makefile', 'Makefile'),
        new Change\CopyFile(__DIR__ . '/../files/phpunit.xml.dist', 'phpunit.xml.dist'),
        new Change\CopyFileIfNotExists(__DIR__ . '/../files/CHANGELOG.md', 'CHANGELOG.md'),
        Change\Env::Change,
        Change\License::Change,
        Change\Readme::Change,
        Change\Src::Change,
        Change\Tests::Change,
        Examples::Change,
        GitHubWorkflow::Change,
        new RemoveFile('.github/workflows/check.yml'),
        PHPCSFixer::Change,
        PHPStan::Change,
        Rector::Change,
    ]))
    ->run();
