<?php

declare(strict_types=1);

namespace App\Tests\Functional\Page;

use App\Repository\Page\PageRepository;
use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testPublicShow(): void
    {
        $this->client->request('GET', '/pages/welcome-on-fastfony');
        $this->assertResponseIsSuccessful();
    }

    public function testPreviewShow(): void
    {
        // Check all routes with user connection
        $superAdmin = static::getContainer()->get(UserRepository::class)
            ->findOneByEmail('superadmin@fastfony.com');
        $this->client->loginUser($superAdmin);

        // We create a new page not enabled
        $this->client->request('GET', '/admin/page-crud/new');
        $this->client->submitForm('ea[newForm][btn]', [
            'Page[name]' => 'Page 2',
            'Page[title]' => 'Welcome on Fastfony 2',
            'Page[enabled]' => false,
        ]);

        // We check the preview of the page
        $this->client->request('GET', '/pages/welcome-on-fastfony-2');
        $this->assertResponseIsSuccessful();

        // When I'm not logged in, I can't see the page
        $this->client->request('GET', '/logout');
        $this->client->request('GET', '/pages/welcome-on-fastfony-2');
        $this->assertResponseStatusCodeSame(404);

        $pageRepository = static::getContainer()->get(PageRepository::class);
        $page = $pageRepository->findOneBy(['slug' => 'welcome-on-fastfony-2']);
        $pageRepository->remove($page);
    }
}
