<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Entity\Notification\AppMessage;
use App\Entity\User\User;
use App\Repository\Notification\AppMessageRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Notifier\Exception\RuntimeException;
use Symfony\Component\Notifier\Exception\UnsupportedMessageTypeException;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppTransport extends AbstractTransport
{
    public const TRANSPORT = 'app';

    public function __construct(
        private readonly AppMessageRepository $appMessageRepository,
        private readonly HubInterface $hub,
        private readonly RouterInterface $router,
        protected ?HttpClientInterface $client = null,
        private readonly ?EventDispatcherInterface $dispatcher = null,
    ) {
        parent::__construct($this->client, $this->dispatcher);
    }

    public function __toString(): string
    {
        return self::TRANSPORT;
    }

    public function supports(MessageInterface $message): bool
    {
        return self::TRANSPORT === $message->getTransport();
    }

    protected function doSend(MessageInterface $message): SentMessage
    {
        if (!$message instanceof ChatMessage) {
            throw new UnsupportedMessageTypeException(self::TRANSPORT, ChatMessage::class, $message);
        }

        /** @var AppNotification $notification */
        $notification = $message->getNotification();
        $user = $notification->recipient;
        if (!$user instanceof User) {
            throw new RuntimeException('The recipient must be an instance of '.User::class);
        }

        // Save in database
        $appMessage = (new AppMessage())
            ->setAppChannel($user->getAppChannel())
            ->setSubject($notification->getSubject())
            ->setContent($notification->getContent())
            ->setImportance($notification->getImportance())
            ->setEmoji($notification->getEmoji())
        ;

        $this->appMessageRepository->save($appMessage);

        // Send with mercure Hub
        $this->hub->publish(new Update(
            $this->router->generate(
                '_api_/app_channels/{id}{._format}_get',
                ['id' => $user->getAppChannel()?->getId()],
                RouterInterface::ABSOLUTE_URL,
            ),
            json_encode([
                'id' => $appMessage->getId(),
                'subject' => $appMessage->getSubject(),
                'content' => $appMessage->getContent(),
                'importance' => $appMessage->getImportance(),
                'emoji' => $appMessage->getEmoji(),
                'createdAt' => $appMessage->getCreatedAt()?->format('c'),
            ])
        ));

        // Create an instance of SentMessage that should be returned to respect the contract.
        $sentMessage = new SentMessage($message, (string) $this);
        $sentMessage->setMessageId((string) $appMessage->getId());

        return $sentMessage;
    }
}
