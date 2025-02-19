<?php

declare(strict_types=1);

namespace App\Installation;

use App\DataFixtures\Installation;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class Step2
{
    public function __construct(
        private KernelInterface $kernel,
    ) {
    }

    public function do(): void
    {
        // We create database, schema and fixtures
        $this->runCommand(
            'doctrine:schema:update',
            ['--force' => true],
        );
        $this->runCommand(
            'doctrine:fixtures:load',
            ['--group' => [Installation::GROUP_INSTALL], '--no-interaction' => true],
        );
    }

    private function runCommand(string $command, array $options = []): void
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(array_merge(['command' => $command], $options));
        $application->run($input, new NullOutput());
    }
}
