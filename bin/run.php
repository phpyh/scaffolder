<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder;

use Symfony\Component\Console\SingleCommandApplication;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new SingleCommandApplication();

Fact\Project::configureCommand($app);
Fact\PackageType::configureCommand($app);
Fact\PackageVendor::configureCommand($app);
Fact\PackageProject::configureCommand($app);
Fact\PhpConstraint::configureCommand($app);
Fact\Authors::configureCommand($app);
Fact\UserName::configureCommand($app);
Fact\UserEmail::configureCommand($app);
Fact\License::configureCommand($app);
Fact\CopyrightHolder::configureCommand($app);
Fact\VendorNamespace::configureCommand($app);

$app
    ->setCode(new Command([
        Change\ComposerJson::Change,
        Change\Compose::Change,
        new Change\CopyFile(__DIR__ . '/../files/infection.json5.dist', 'infection.json5.dist'),
        Change\Changelog::Change,
        Change\Makefile::Change,
        Change\License::Change,
        Change\Gitignore::Change,
        Change\Readme::Change,
        Change\Src::Change,
        Change\Tests::Change,
        Change\Bin::Change,
        Change\Examples::Change,
        Change\Gitattributes::Change,
        Change\GitHubWorkflow::Change,
        new Change\Remove('.github/workflows/check.yml'),
        new Change\Remove('tools'),
        new Change\Remove('psalm.xml.dist'),
        new Change\Remove('.devcontainer.json'),
        Change\RemoveDotEnv::Change,
        Change\PHPCSFixer::Change,
        Change\PHPStan::Change,
        Change\Rector::Change,
        Change\Testo::Change,
    ]))
    ->run();
