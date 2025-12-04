<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Change;

use PHPyh\Scaffolder\Change;
use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact\CopyrightHolder;
use PHPyh\Scaffolder\Fact\License as LicenseFact;
use PHPyh\Scaffolder\Fact\Project;
use PHPyh\Scaffolder\Facts;

enum License implements Change
{
    case Change;

    public function decide(Facts $facts, Project $project): ?callable
    {
        if ($project->exists('LICENSE')) {
            return null;
        }

        if ($facts[LicenseFact::class] !== LicenseFact::MIT) {
            return null;
        }

        $year = date('Y');
        $contents = <<<LICENSE
            MIT License

            Copyright (c) {$year}-present {$facts[CopyrightHolder::class]}
            
            Permission is hereby granted, free of charge, to any person obtaining a copy
            of this software and associated documentation files (the "Software"), to deal
            in the Software without restriction, including without limitation the rights
            to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
            copies of the Software, and to permit persons to whom the Software is
            furnished to do so, subject to the following conditions:
            
            The above copyright notice and this permission notice shall be included in all
            copies or substantial portions of the Software.
            
            THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
            IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
            FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
            AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
            LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
            OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
            SOFTWARE.
            LICENSE;

        return static fn(Cli $cli) => $cli->step(
            'Write `LICENSE`...',
            static fn() => $project->write('LICENSE', $contents),
        );
    }
}
