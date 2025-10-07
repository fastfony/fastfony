<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Repository\Notification\AppMessageRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AutoconfigureTag('chatter.transport_factory')]
class AppTransportFactory extends AbstractTransportFactory
{
    public function __construct(
        private readonly AppMessageRepository $appMessageRepository,
        private readonly HubInterface $hub,
        private readonly RouterInterface $router,
        protected ?EventDispatcherInterface $dispatcher = null,
        protected ?HttpClientInterface $client = null,
    ) {
        parent::__construct($this->dispatcher, $this->client);
    }

    /**
     * @return array<int, string>
     */
    protected function getSupportedSchemes(): array
    {
        return [AppTransport::TRANSPORT];
    }

    public function create(Dsn $dsn): TransportInterface
    {
        return new AppTransport(
            $this->appMessageRepository,
            $this->hub,
            $this->router,
        );
    }
}
