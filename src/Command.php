<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder;

use PHPyh\Scaffolder\Fact\Project;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class Command
{
    /**
     * @param list<Change> $changes
     */
    public function __construct(
        private array $changes,
    ) {}

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $cli = new Cli($input, $output);
        $facts = new Facts($cli);
        $project = $facts[Project::class];

        $appliers = [];

        foreach ($this->changes as $change) {
            $decision = $change->decide($facts, $project);

            if ($decision !== null) {
                $appliers[] = $decision;
            }
        }

        if ($appliers === []) {
            $cli->success('No changes required');

            return SymfonyCommand::SUCCESS;
        }

        foreach ($appliers as $applier) {
            $applier($cli);
        }

        $cli->success(\sprintf('Applied %d change%s', \count($appliers), \count($appliers) === 1 ? '' : 's'));

        return SymfonyCommand::SUCCESS;
    }
}
