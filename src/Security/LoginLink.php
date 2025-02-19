<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;

class LoginLink
{
    private const LIFETIME = 300;
    private const MAX_ASK = 3;

    public function __construct(
        private NotifierInterface $notifier,
        private LoginLinkHandlerInterface $loginLinkHandler,
        private CacheItemPoolInterface $cache,
        private ?string $subjectEmailLoginLink = null,
    ) {
    }

    public function send(User $user): bool
    {
        // We limit the number of login link requests in time period
        if ($this->hasAlreadyTooMuchAsk($user)) {
            return false;
        }

        $notification = new LoginLinkNotification(
            $this->loginLinkHandler->createLoginLink($user, null, self::LIFETIME),
            $this->subjectEmailLoginLink ?? 'Your login link',
        );
        $recipient = new Recipient($user->getEmail());
        try {
            $this->notifier->send($notification, $recipient);

            $this->saveAskTimeInCache($user);

            return true;
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }

    private function hasAlreadyTooMuchAsk(User $user): bool
    {
        return \count($this->getUserRecentlyAskLoginTime($user)) >= self::MAX_ASK;
    }

    private function getUserRecentlyAskLoginTime(User $user): array
    {
        $this->cache->prune();
        $itemCache = $this->cache->getItem($this->getCacheItemUserKey($user));
        if (!$itemCache->isHit()) {
            return [];
        }

        return $itemCache->get();
    }

    private function getCacheItemUserKey(User $user): string
    {
        return 'user_'.$user->getId().'_has_recently_ask_login_link';
    }

    private function saveAskTimeInCache(User $user): void
    {
        $itemCache = $this->cache->getItem($this->getCacheItemUserKey($user));
        $asks = $itemCache->get();
        $asks[] = time();
        $itemCache->set($asks);
        $itemCache->expiresAfter(self::LIFETIME / 10); // New send login link is authorized after 10% of the lifetime
        $this->cache->save($itemCache);
    }
}
