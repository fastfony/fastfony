<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Page\Page;
use App\Repository\Page\PageRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    protected function setUp(): void
    {
        self::ensureKernelShutdown();
    }

    public function testHomepageDouble(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome on Fastfony');

        $pageRepository = static::getContainer()->get(PageRepository::class);
        $homepage2 = (new Page())
            ->setHomepage(true)
            ->setName('Homepage 2')
            ->setTitle('Homepage 2')
            ->setEnabled(true)
        ;
        $pageRepository->save($homepage2);

        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(404, 'You have multiple homepages configured.');

        $homepage2 = $pageRepository->findOneBy(['slug' => 'homepage-2']);
        $pageRepository->remove($homepage2);
    }
}
