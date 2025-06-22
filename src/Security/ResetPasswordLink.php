<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User\RequestPassword;
use App\Entity\User\User;
use App\Repository\RequestPasswordRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordLink
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly RequestPasswordRepository $requestPasswordRepository,
        private readonly TranslatorInterface $translator,
        private readonly string $mailerSender,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(User $user): bool
    {
        $requestPassword = (new RequestPassword())
            ->setUser($user);

        $this->requestPasswordRepository->save($requestPassword);

        $email = (new TemplatedEmail())
            ->from($this->mailerSender)
            ->to(new Address($user->getEmail()))
            ->subject($this->translator->trans('reset_password.email.subject'))
            ->htmlTemplate('security/emails/reset_password.html.twig')
            ->context([
                'user' => $user,
                'request_password' => $requestPassword,
            ])
        ;

        try {
            $this->mailer->send($email);

            return true;
        } catch (\Exception) {
            return false;
        }
    }
}
