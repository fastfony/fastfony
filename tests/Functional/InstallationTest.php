<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
     * @depends App\Tests\Functional\Security\RegisterTest::testRegistrationDisabled
     */
    public function testFailedSteps(): void
    {
        $this->client->followRedirects();
        $this->client->request('GET', '/installation/2');
        $this->client->submitForm('Create super admin user', [
            'login_form[email]' => 'test',
        ]);

        $this->assertSelectorExists('#toast-container .alert.alert-error');
    }

    /**
     * We add depends tests here in order to be the lasts tests.
     *
     * @depends testFailedSteps
     */
    public function testSuccessSteps(): void
    {
        $this->client->request('GET', '/installation');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', '/installation/2');
        $this->client->submitForm('Create super admin user', [
            'login_form[email]' => 'test@test.com',
        ]);
        $this->assertResponseStatusCodeSame(303);
        $this->assertSelectorCount(3, '.step.step-primary');
    }
}
