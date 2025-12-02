<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class Cli
{
    private SymfonyStyle $style;

    public function __construct(
        private InputInterface $input,
        OutputInterface $output,
    ) {
        $this->style = new SymfonyStyle($input, $output);
    }

    public function getArgument(string $name): mixed
    {
        return $this->input->getArgument($name);
    }

    public function getOption(string $name): mixed
    {
        return $this->input->getOption($name);
    }

    /**
     * @param non-empty-string $name
     * @param callable(): mixed $step
     */
    public function step(string $name, callable $step): void
    {
        $this->style->write($name);
        $step();
        $this->style->writeln(' Done.');
    }

    /**
     * @no-named-arguments
     */
    public function success(string ...$lines): void
    {
        $this->style->success($lines);
    }

    /**
     * @template T of scalar|array|object
     * @param non-empty-string $question
     * @param callable(string): ?T $normalizer
     * @return T
     */
    public function ask(string $question, callable $normalizer, ?string $default = null): mixed
    {
        do {
            $answer = $this->style->ask($question, $default) ?? '';
            \assert(\is_string($answer));

            $normalized = $normalizer($answer);
        } while ($normalized === null);

        return $normalized;
    }

    /**
     * @template T of (scalar|array|object)
     * @param non-empty-string $question
     * @param non-empty-list<T> $choices
     * @param ?T $default
     * @return T
     * @phpstan-ignore missingType.iterableValue
     */
    public function askChoice(string $question, array $choices, mixed $default = null): mixed
    {
        return $this->style->choice($question, $choices, $default); // @phpstan-ignore return.type
    }
}
