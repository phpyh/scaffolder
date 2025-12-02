<?php

declare(strict_types=1);

namespace PHPyh\Scaffolder\Fact;

use PHPyh\Scaffolder\Cli;
use PHPyh\Scaffolder\CommandConfigurator;
use PHPyh\Scaffolder\Fact;
use PHPyh\Scaffolder\Facts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

/**
 * @extends Fact<self>
 */
final class Project extends Fact implements CommandConfigurator
{
    public static function configureCommand(Command $command): void
    {
        $command->addArgument('dir', default: getcwd());
    }

    public static function resolve(Facts $facts, Cli $cli): self
    {
        $dir = $cli->getArgument('dir');
        \assert(\is_string($dir));

        if (!is_dir($dir)) {
            throw new \RuntimeException(\sprintf('Directory `%s` does not exist', $dir));
        }

        return new self($dir);
    }

    private function __construct(
        public readonly string $dir,
        private readonly Filesystem $fs = new Filesystem(),
    ) {
        parent::__construct();
    }

    public function locate(string $path): string
    {
        if ($this->fs->isAbsolutePath($path)) {
            return $path;
        }

        return $this->dir . '/' . $path;
    }

    public function exists(string $path): bool
    {
        return $this->fs->exists($this->locate($path));
    }

    /**
     * @throws IOException
     */
    public function read(string $file): string
    {
        return $this->fs->readFile($this->locate($file));
    }

    public function tryRead(string $file): ?string
    {
        try {
            return $this->read($file);
        } catch (IOException) {
            return null;
        }
    }

    public function write(string $file, string $contents = ''): void
    {
        if ($contents !== '' && !str_ends_with($contents, "\n")) {
            $contents .= "\n";
        }

        $this->fs->dumpFile($this->locate($file), $contents);
    }

    public function remove(string $path): void
    {
        $this->fs->remove($this->locate($path));
    }

    public function copy(string $originFile, string $targetFile): void
    {
        $this->fs->copy(
            originFile: $this->locate($originFile),
            targetFile: $this->locate($targetFile),
            overwriteNewerFiles: true,
        );
    }

    public function directoryHasAnyFiles(string $directory): bool
    {
        try {
            return new Finder()
                ->in($this->locate($directory))
                ->files()
                ->ignoreDotFiles(false)
                ->hasResults();
        } catch (DirectoryNotFoundException) {
            return false;
        }
    }

    /**
     * @param non-empty-string $command
     */
    public function execute(string $command): string
    {
        exec("cd {$this->dir} && {$command}", $lines, $code);

        $output = implode("\n", $lines);

        if ($code > 0) {
            throw new \RuntimeException("Command execution failed\n\n$ {$command}\n\n{$output}");
        }

        return $output;
    }
}
