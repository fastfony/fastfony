<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\ContactRequest;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsEntityListener(event: 'postPersist', entity: ContactRequest::class)]
class CreateContactRequest
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $mailerSender,
        private readonly string $appName,
        private readonly string $appContactEmail,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function postPersist(ContactRequest $contactRequest): void
    {
        $email = (new Email())
            ->from($this->mailerSender)
            ->to($this->appContactEmail)
            ->subject('New contact request from '.$this->appName)
            ->text(
                'From : '.$contactRequest->getFirstName().' '.$contactRequest->getLastName().'
                Email : '.$contactRequest->getEmail().'
                Phone : '.$contactRequest->getPhoneNumber().'
                Message : '.$contactRequest->getMessage()
            );

        try {
            $this->mailer->send($email);
        } catch (TransportException $t) {
            $this->logger->error('Email sending failed: '.$t->getMessage());
        }
    }
}
