<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\EventSubscriber\OAuth2Server;

use App\Entity\User\User;
use App\Pro\Entity\OAuth2Server\Client;
use App\Pro\EventSubscriber\OAuth2Server\AuthorizationCodeSubscriber;
use Doctrine\Common\Collections\ArrayCollection;
use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use League\Bundle\OAuth2ServerBundle\OAuth2Events;
use PHPUnit\Framework\TestCase;

final class AuthorizationCodeSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $expectedEvents = [
            OAuth2Events::AUTHORIZATION_REQUEST_RESOLVE => 'onLeagueOauth2ServerEventAuthorizationRequestResolve',
        ];

        $this->assertSame($expectedEvents, AuthorizationCodeSubscriber::getSubscribedEvents());
    }

    public function testOnAuthorizationCodeIssued(): void
    {
        $event = $this->createMock(AuthorizationRequestResolveEvent::class); // Thanks to dg/bypass-finals
        $subscriber = new AuthorizationCodeSubscriber();

        $user = $this->createMock(User::class);

        $client = $this->createMock(Client::class);

        $user->method('getClients')
            ->willReturn(new ArrayCollection([$client]));

        $event->expects($this->exactly(2))
            ->method('getUser')
            ->willReturn($user);

        $event->expects($this->once())
            ->method('getClient')
            ->willReturn($client);

        $subscriber->onLeagueOauth2ServerEventAuthorizationRequestResolve($event);
    }
}
