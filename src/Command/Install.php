<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:install',
    description: 'Prepare the application',
)]
class Install extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (file_exists('.env.local')) {
            $io = new SymfonyStyle($input, $output);
            $io->success('The application seems to be installed.');

            return Command::SUCCESS;
        }

        $io = new SymfonyStyle($input, $output);
        $envContent = [];

        $io->title('Welcome on Fastfony!');
        try {
            $this->configureDatabase($io, $envContent);
            $this->createDatabaseAndFixtures($output);
        } catch (\Throwable $e) {
            $io->error('An error occurred while configuring or creating the database: '.$e->getMessage());

            return Command::FAILURE;
        }

        $this->configureMailerDsn($io, $envContent);

        // Generate APP_SECRET and FEATURE_FLAGS empty
        $appSecret = bin2hex(random_bytes(16));
        $envContent[] = \sprintf('APP_SECRET="%s"', $appSecret);
        $envContent[] = "FEATURE_FLAGS='{}'";

        // Write to .env.local
        file_put_contents('.env.local', implode("\n", $envContent));
        chmod('.env.local', 0666);

        $io->success('Installation completed!');
        $io->info(
            'For install Symfony local proxy: '
             .'https://symfony.com/doc/current/setup/symfony_server.html#setting-up-the-local-proxy',
        );

        return Command::SUCCESS;
    }

    /**
     * @param array <int, string> $envContent
     */
    private function configureDatabase(
        SymfonyStyle $io,
        array &$envContent,
    ): void {
        $io->section('Database Configuration');
        $keepSqlite = $io->askQuestion(
            new ConfirmationQuestion(
                'Keep SQLite as default database?',
                true,
            )
        );

        if (!$keepSqlite) {
            $dbType = $io->ask('Database type (e.g., mysql/postgresql', 'mysql');
            $dbVersion = $io->ask('Database version', '10');
            $dbHost = $io->ask('Database host', 'localhost');
            $dbPort = $io->ask('Database port', '3306');
            $dbName = $io->ask('Database name', 'fastfony');
            $dbUser = $io->ask('Database user');
            $dbPass = $io->askHidden('Database password');

            $envContent[] = \sprintf(
                'DATABASE_URL="%s://%s:%s@%s:%s/%s?serverVersion=%s&charset=utf8mb4"',
                $dbType,
                $dbUser,
                $dbPass,
                $dbHost,
                $dbPort,
                $dbName,
                $dbVersion,
            );
        }
    }

    private function createDatabaseAndFixtures(OutputInterface $output): void
    {
        $this->getApplication()->doRun(
            new ArrayInput([
                'command' => 'doctrine:database:create',
                '--env' => 'dev',
            ]),
            $output,
        );

        $this->getApplication()->doRun(
            new ArrayInput([
                'command' => 'doctrine:schema:update',
                '--force' => true,
                '--env' => 'dev',
            ]),
            $output,
        );

        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--group' => ['install'],
            '--env' => 'dev',
        ]);
        $input->setInteractive(false);
        $this->getApplication()->doRun(
            $input,
            $output,
        );
    }

    /**
     * @param array <int, string> $envContent
     */
    private function configureMailerDsn(
        SymfonyStyle $io,
        array &$envContent,
    ): void {
        $io->section('Mailer Configuration');
        $keepDefaultMailer = $io->askQuestion(
            new ConfirmationQuestion(
                'Keep default mailer configuration (with Mailpit catch-all at http://127.0.0.1:8025) ?',
                true,
            )
        );

        if (!$keepDefaultMailer) {
            $smtpHost = $io->ask('SMTP host');
            $smtpPort = $io->ask('SMTP port');
            $smtpUser = $io->ask('SMTP user');
            $smtpPass = $io->ask('SMTP password');

            $envContent[] = \sprintf(
                'MAILER_DSN="smtp://%s:%s@%s:%s"',
                $smtpUser,
                $smtpPass,
                $smtpHost,
                $smtpPort,
            );
        }
    }
}
