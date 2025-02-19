<?php

declare(strict_types=1);

namespace App\HealthCheck;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    public function __construct(
        private MailerInterface $mailer,
        private RequestStack $requestStack,
    ) {
    }

    public function check(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return false;
        }

        $email = (new Email())
            ->from('noreply@'.$request->getHost())
            ->to('install@fastfony.com')
            ->subject('Fastfony install test email')
            ->text('This is a test email. Fastfony is installed on '.gethostname().' in '.getcwd().' and the host is '.$request->getHost());

        try {
            $this->mailer->send($email);

            return true;
        } catch (TransportExceptionInterface) {
            return false;
        }
    }
}
