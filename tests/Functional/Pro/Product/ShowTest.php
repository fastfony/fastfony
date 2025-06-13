<?php

declare(strict_types=1);

namespace App\Tests\Functional\Pro\Product;

use App\Pro\Repository\Product\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ShowTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $client = static::createClient();

        $productRepository = static::getContainer()->get(ProductRepository::class);
        $product = $productRepository->findOneBy(['enabled' => true]);
        $this->assertNotNull($product);

        $client->request('GET', '/product/'.$product->getSlug());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $product->getName());

        // Try to see buy page
        $client->request('GET', '/product/buy/'.$product->getId());
        $this->assertResponseRedirects('/product/'.$product->getSlug());
        $client->followRedirect();
        // An error message should be displayed because Stripe is not enabled
        $this->assertSelectorExists('#toast-container .alert.alert-error');

        // Try to see buy cancel page
        $client->request('GET', '/product/buy/'.$product->getId().'/cancel');
        $this->assertResponseIsSuccessful();

        // Try to see buy success page, but it's impossible without session_id and with stripe referer
        $client->request('GET', '/product/buy/'.$product->getId().'/success');
        $this->assertResponseRedirects('/product/buy/'.$product->getId().'/cancel');
    }

    public function testNotFound(): void
    {
        $client = static::createClient();

        $productRepository = static::getContainer()->get(ProductRepository::class);
        $product = $productRepository->findOneBy(['enabled' => true]);
        $product->setEnabled(false);
        $productRepository->save($product);

        $client->request('GET', '/product/'.$product->getSlug());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
