<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder;

use Symfony\Component\Console\Command\Command;

interface CommandConfigurator
{
    public static function configureCommand(Command $command): void;
}
