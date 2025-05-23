<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LoginTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->ensureKernelShutdown();
        $this->client = static::createClient();
    }

    public function testSendAndUseLoginLink(): void
    {
        $this->client->request('GET', '/request-login-link');
        $this->sendFormLoginLink('superadmin@fastfony.com');
        $this->assertSelectorExists('svg.size-12.text-green-600');
        /** @var NotificationEmail $email */
        $email = $this->getMailerMessage();
        $this->client->followRedirects();
        $this->client->request('GET', $email->getContext()['action_url']);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('a[href="/logout"]');
    }

    /**
     * In order to not use the email limits for the testSendAndUserLoginLink test, this one must be in last
     * Be careful, this test is not isolated and can be affected results due to the cache system for the email limits.
     */
    #[Depends('testSendAndUseLoginLink')]
    public function testAccessAdminWithoutAuthentification(): void
    {
        $this->client->followRedirects(); // The admin page is redirected to the login page
        $this->client->request('GET', '/admin');
        $this->assertSelectorExists('form input#username');

        // We use the request login link for this test
        $this->client->request('GET', '/request-login-link');

        // The login form is submitted with invalid credentials
        $this->sendFormLoginLink('unknow@fastfony.com');

        // It's a fake success and no email is sent
        $this->assertSelectorExists('svg.size-12.text-green-600');
        $this->assertEmailCount(0);

        $this->client->request('GET', '/request-login-link');
        $this->sendFormLoginLink('superadmin@fastfony.com');

        // It's a success and an email is sent
        $this->assertSelectorExists('svg.size-12.text-green-600');
        $this->assertEmailCount(1);
        // Here 3 emails are sent, the limit is reached

        // Try to spam the login form request
        for ($i = 0; $i < 10; ++$i) {
            $this->sendFormLoginLink('superadmin@fastfony.com');
            $this->assertSelectorExists('svg.size-12.text-green-600'); // Fake success
            $this->assertEmailCount(0); // After 3 emails, no more email is sent
        }
    }

    private function sendFormLoginLink(string $email): void
    {
        $this->client->request('GET', '/request-login-link');
        $this->client->submitForm('Send me a login link', [
            'login_form[email]' => $email,
        ]);
    }
}
