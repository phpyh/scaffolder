<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder;

/**
 * @template T
 */
abstract class Fact
{
    /**
     * @return T
     */
    abstract public static function resolve(Facts $facts, Cli $cli): mixed;

    protected function __construct() {}
}
