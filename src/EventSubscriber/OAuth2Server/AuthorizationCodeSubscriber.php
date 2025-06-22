<?php

declare(strict_types=1);

namespace App\EventSubscriber\OAuth2Server;

use App\Entity\User\User;
use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use League\Bundle\OAuth2ServerBundle\OAuth2Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorizationCodeSubscriber implements EventSubscriberInterface
{
    public function onLeagueOauth2ServerEventAuthorizationRequestResolve(AuthorizationRequestResolveEvent $event): void
    {
        // We check if the user has authorization for this client
        $authorization = false;
        if ($event->getUser() instanceof User) {
            foreach ($event->getUser()->getClients() as $client) {
                if ($event->getClient() === $client) {
                    $authorization = true;
                }
            }
        }

        $event->resolveAuthorization($authorization);
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OAuth2Events::AUTHORIZATION_REQUEST_RESOLVE => 'onLeagueOauth2ServerEventAuthorizationRequestResolve',
        ];
    }
}
