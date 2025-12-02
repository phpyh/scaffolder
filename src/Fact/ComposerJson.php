<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * @phpstan-type Autoload = array{"psr-4"?: array<non-empty-string, non-empty-string>}
 * @phpstan-type Author = array{name?: string, email?: string, homepage?: string, role?: string}
 * @phpstan-type Type = array{
 *     name?: non-empty-string,
 *     description?: string,
 *     type?: non-empty-string,
 *     license?: string|list<string>,
 *     require?: array<non-empty-string, non-empty-string>,
 *     "require-dev"?: array<non-empty-string, non-empty-string>,
 *     autoload?: Autoload,
 *     "autoload-dev"?: Autoload,
 *     config?: array{"sort-packages"?: bool, lock?: bool, ...},
 *     authors?: list<Author>,
 *     ...
 * }
 * @extends Fact<Type>
 */
final class ComposerJson extends Fact
{
    public static function resolve(Facts $facts, Cli $cli): mixed
    {
        try {
            $json = $facts[Project::class]->read('composer.json');
        } catch (IOException) {
            return [];
        }

        try {
            /** @var Type */
            return json_decode($json, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return [];
        }
    }
}
