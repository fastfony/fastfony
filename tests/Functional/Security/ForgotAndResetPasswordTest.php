<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mime\Email;

class ForgotAndResetPasswordTest extends WebTestCase
{
    private string $email = 'test-user@example.com';
    private string $newPassword = 'NewSecurePassword123!';

    public function testCompleteResetPasswordFlow(): void
    {
        $client = static::createClient();

        $this->prepareUserAccount();

        $client->request('GET', '/forgot-password');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Reset your password');

        // Soumettre le formulaire d'oubli de mot de passe
        $crawler = $client->submitForm('Send the link', [
            'request_password_form[email]' => $this->email,
        ]);

        $this->assertSelectorExists('svg.text-green-600'); // Green tick icon

        $this->assertEmailCount(1);
        /** @var Email $email */
        $email = $this->getMailerMessage();

        $this->assertEmailHeaderSame($email, 'To', $this->email);
        $this->assertEmailSubjectContains($email, 'Reset your password');

        // Extract the reset link from the email content
        $emailContent = $email->getHtmlBody();
        preg_match('/(\/reset-password\/[a-zA-Z0-9_-]+)/', $emailContent, $matches);
        $resetLink = $matches[1];

        $client->request('GET', $resetLink);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Choose a new password');

        $client->submitForm('Save this password and connect', [
            'form[password]' => $this->newPassword,
        ]);

        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertSelectorTextContains('.navbar-end', 'Logout');
    }

    private function prepareUserAccount(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => $this->email]);

        if (!$user) {
            $user = (new User())
                ->setEmail($this->email)
            ;

            $hasher = static::getContainer()->get('security.user_password_hasher');
            $hashedPassword = $hasher->hashPassword($user, 'OldPassword123!');
            $user->setPassword($hashedPassword);

            $user->setEnabled(true);

            $userRepository->save($user);
        }
    }

    protected function tearDown(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => $this->email]);
        if ($user) {
            $userRepository->remove($user);
        }

        parent::tearDown();
    }
}
