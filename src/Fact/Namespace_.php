<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<string>
 */
final class Namespace_ extends Fact
{
    private const string REGEX = '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*+(?>\\\[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*+)++$/';

    public static function resolve(Facts $facts, Cli $cli): string
    {
        $composerJson = $facts[ComposerJson::class];

        $namespace = array_key_first($composerJson['autoload']['psr-4'] ?? []);

        if ($namespace !== null) {
            return rtrim($namespace, '\\');
        }

        return $cli->ask(
            question: 'Namespace',
            default: self::pascalize($facts[PackageVendor::class]) . '\\' . self::pascalize($facts[PackageProject::class]),
            normalizer: self::normalize(...),
        );
    }

    private static function normalize(string $namespace): string
    {
        if ($namespace === '' || preg_match(self::REGEX, $namespace) === 1) {
            return $namespace;
        }

        throw new \InvalidArgumentException('Invalid namespace');
    }

    private static function pascalize(string $name): string
    {
        $namespace = preg_replace_callback(
            '~[-_.]+(\w)~',
            static fn(array $matches) => strtoupper($matches[1]),
            $name,
        );
        \assert(\is_string($namespace));

        return ucfirst($namespace);
    }
}
