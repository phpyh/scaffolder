<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder;

/**
 * @implements \ArrayAccess<class-string<Fact<*>>, mixed>
 */
final class Facts implements \ArrayAccess
{
    public function __construct(
        private readonly Cli $cli,
    ) {}

    /**
     * @var array<class-string<Fact<*>>, mixed>
     */
    private array $facts = [];

    public function offsetExists(mixed $offset): bool
    {
        return \array_key_exists($offset, $this->facts);
    }

    /**
     * @template T
     * @param class-string<Fact<T>> $offset
     * @return T
     */
    public function offsetGet(mixed $offset): mixed // @phpstan-ignore method.childParameterType
    {
        if (\array_key_exists($offset, $this->facts)) {
            return $this->facts[$offset];
        }

        return $this->facts[$offset] = $offset::resolve($this, $this->cli);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \BadMethodCallException();
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \BadMethodCallException();
    }
}
