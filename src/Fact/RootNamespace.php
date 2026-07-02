<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;

/**
 * @extends Fact<string>
 */
final class RootNamespace extends Fact
{
    private const string NAME = '[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*+';
    private const string REGEX = '/^' . self::NAME . '(?>\\\\' . self::NAME . ')*+$/';

    public static function resolve(Facts $facts, Cli $cli): string
    {
        $composerJson = $facts[ComposerJsonContents::class];

        $namespace = array_key_first($composerJson['autoload']['psr-4'] ?? []);

        if ($namespace !== null) {
            return rtrim($namespace, '\\');
        }

        return $cli->ask(
            question: 'Namespace',
            default: self::resolveDefault($facts),
            normalizer: self::normalize(...),
        );
    }

    private static function resolveDefault(Facts $facts): ?string
    {
        $projectNamespace = self::pascalize($facts[PackageProject::class]);

        $default = $facts[VendorNamespace::class] . '\\' . $projectNamespace;

        if (self::isValid($default)) {
            return $default;
        }

        $default = $facts[VendorNamespace::class] . $projectNamespace;

        if (self::isValid($default)) {
            return $default;
        }

        return null;
    }

    private static function isValid(string $namespace): bool
    {
        return preg_match(self::REGEX, $namespace) === 1;
    }

    public static function normalize(string $namespace): string
    {
        if ($namespace === '' || self::isValid($namespace)) {
            return $namespace;
        }

        throw new \InvalidArgumentException(\sprintf('Invalid namespace `%s`', $namespace));
    }

    public static function pascalize(string $name): string
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
