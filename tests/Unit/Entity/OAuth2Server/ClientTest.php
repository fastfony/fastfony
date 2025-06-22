<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\OAuth2Server;

use App\Entity\OAuth2Server\Client;
use League\Bundle\OAuth2ServerBundle\ValueObject\Grant;
use League\Bundle\OAuth2ServerBundle\ValueObject\RedirectUri;
use League\Bundle\OAuth2ServerBundle\ValueObject\Scope;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testGetScopeStrings(): void
    {
        $client = new Client('client_id', 'client_name', 'client_secret');
        $scope = new Scope('scope1');
        $client->setScopes($scope);

        $this->assertSame(['scope1'], $client->getScopeStrings());
    }

    public function testSetScopeStrings(): void
    {
        $client = new Client('client_id', 'client_name', 'client_secret');
        $client->setScopeStrings(['scope1', 'scope2']);

        $this->assertCount(2, $client->getScopes());
        $this->assertSame('scope1', $client->getScopes()[0]->__toString());
        $this->assertSame('scope2', $client->getScopes()[1]->__toString());
    }

    public function testGetGrantStrings(): void
    {
        $client = new Client('client_id', 'client_name', 'client_secret');
        $grant = new Grant('grant1');
        $client->setGrants($grant);

        $this->assertSame(['grant1'], $client->getGrantStrings());
    }

    public function testSetGrantStrings(): void
    {
        $client = new Client('client_id', 'client_name', 'client_secret');
        $client->setGrantStrings(['grant1', 'grant2']);

        $this->assertCount(2, $client->getGrants());
        $this->assertSame('grant1', $client->getGrants()[0]->__toString());
        $this->assertSame('grant2', $client->getGrants()[1]->__toString());
    }

    public function testGetRedirectUriStrings(): void
    {
        $client = new Client('client_id', 'client_name', 'client_secret');
        $redirectUri = new RedirectUri('http://example.com');
        $client->setRedirectUris($redirectUri);

        $this->assertSame(['http://example.com'], $client->getRedirectUriStrings());
    }

    public function testSetRedirectUriStrings(): void
    {
        $client = new Client('client_id', 'client_name', 'client_secret');
        $client->setRedirectUriStrings(['http://example.com', 'http://example.org']);

        $this->assertCount(2, $client->getRedirectUris());
        $this->assertSame('http://example.com', $client->getRedirectUris()[0]->__toString());
        $this->assertSame('http://example.org', $client->getRedirectUris()[1]->__toString());
    }
}
