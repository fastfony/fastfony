<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Process\Process;

class InstallationTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        try {
            $userRepository = self::getContainer()->get(UserRepository::class);
            // We disable all users for permit the installation process
            foreach ($userRepository->findAll() as $user) {
                $user->setEnabled(false);
                $userRepository->save($user);
            }
        } catch (\Exception $e) {
            // We can have an exception if the database is not created
        }

        self::ensureKernelShutdown();

        $this->client = static::createClient();
    }

    /**
     * We add depends tests here in order to be the lasts tests.
     *
     * @depends App\Tests\Functional\Security\RegisterTest::testRegisterFailed
     */
    public function testFailedAndSuccessSteps(): void
    {
        $this->client->request('GET', '/installation');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('It\'s OK', [
            'features_form[features]' => [
                'users_management',
            ],
        ]);

        $this->client->submitForm('Create super admin user', [
            'installation_form[email]' => 'test',
            'installation_form[licenseKey]' => 'test',
        ]);

        $this->assertSelectorExists('#toast-container .alert.alert-error');

        $this->client->submitForm('Create super admin user', [
            'installation_form[email]' => 'test@test.com',
            'installation_form[licenseKey]' => 'test',
        ]);

        $this->assertSelectorCount(1, '.text-error');
    }
}
