<?php

declare(strict_types=1);

namespace App\Tests\Functional\Page;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testPageContactIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Contact');
    }

    public function testContactFormExists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertCount(1, $crawler->filter('form[name="contact_request"]'));
        $this->assertCount(1, $crawler->filter('input[name="contact_request_form[firstName]"]'));
        $this->assertCount(1, $crawler->filter('input[name="contact_request_form[lastName]"]'));
        $this->assertCount(1, $crawler->filter('input[name="contact_request_form[email]"]'));
        $this->assertCount(1, $crawler->filter('input[name="contact_request_form[phoneNumber]"]'));
        $this->assertCount(1, $crawler->filter('textarea[name="contact_request_form[message]"]'));
        $this->assertCount(1, $crawler->filter('input#agreement'));
        $this->assertCount(1, $crawler->filter('button[type="submit"]'));
    }

    public function testContactFormSubmissionWithInvalidData(): void
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $client->submitForm('Send a Message', [
            'contact_request_form[email]' => 'email-invalide',
            'contact_request_form[message]' => 'Test message',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertSelectorExists('.text-error');
    }

    public function testContactFormSubmissionWithValidData(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/contact');

        $client->submitForm('Send a Message', [
            'contact_request_form[email]' => 'test@example.com',
            'contact_request_form[message]' => 'Ceci est un message de test.',
        ]);

        $this->assertSelectorExists('.alert-success');
    }
}
