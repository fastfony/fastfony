<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Repository\Parameter\ParameterRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRegisterSuccess(): void
    {
        $this->client->request('GET', '/register');
        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Create an account', [
            'register_form[email]' => 'newuser@fastfony.com',
        ]);

        $this->assertSelectorExists('svg.size-12.text-green-600');
        $this->assertEmailCount(1);
    }

    public function testRegisterFailed(): void
    {
        $this->client->request('GET', '/register');
        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Create an account', [
            'register_form[email]' => 'newuser_at_fastfony',
        ]);

        $this->assertSelectorTextContains('label', 'This value is not a valid email address.');
        $this->assertEmailCount(0);
    }

    public function testRegistrationDisabled(): void
    {
        $parameterRepository = static::getContainer()->get(ParameterRepository::class);
        $parameter = $parameterRepository->findOneBy(['key' => 'REGISTRATION_ENABLED']);
        $parameter->setValue('0');
        $parameterRepository->save($parameter);

        $this->client->request('GET', '/register');
        $this->assertResponseStatusCodeSame(404);
    }
}
