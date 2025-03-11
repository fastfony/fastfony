<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User\User;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Mime\Exception\RfcComplianceException;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginLink
{
    private const LIFETIME = 300;
    private const MAX_ASK = 3;

    /**
     * @param FilesystemAdapter $cache
     */
    public function __construct(
        private readonly NotifierInterface $notifier,
        private readonly LoginLinkHandlerInterface $loginLinkHandler,
        private readonly CacheInterface $cache,
        private readonly TranslatorInterface $translator,
        private readonly string $companyName,
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
            $this->translator->trans('login.email.subject', ['%companyName%' => $this->companyName]),
        );
        $recipient = new Recipient($user->getEmail());
        try {
            $this->notifier->send($notification, $recipient);

            $this->saveAskTimeInCache($user);

            return true;
        } catch (HandlerFailedException|RfcComplianceException|TransportExceptionInterface $e) {
            return false;
        }
    }

    private function hasAlreadyTooMuchAsk(User $user): bool
    {
        return \count($this->getUserRecentlyAskLoginTime($user)) >= self::MAX_ASK;
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return array<int>
     */
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
