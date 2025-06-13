<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Entity\Product\Stripe;

use App\Pro\Entity\Product\Price as ProductPriceEntity;
use App\Pro\Entity\Product\Product;
use App\Pro\Enum\RecurringInterval;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stripe\Price as StripeApiPrice;
use Stripe\Service\PriceService;
use Stripe\StripeClient;

class PriceTest extends TestCase
{
    private MockObject|StripeClient $stripeClientMock;
    private MockObject|PriceService $priceServiceMock;
    private string $defaultLocale;

    protected function setUp(): void
    {
        parent::setUp();

        // Store original default locale and set a specific one for tests
        $this->defaultLocale = \Locale::getDefault();
        \Locale::setDefault('en_US');

        $this->priceServiceMock = $this->createMock(PriceService::class);
        $this->stripeClientMock = $this->createMock(StripeClient::class);
        $this->stripeClientMock->prices = $this->priceServiceMock;
    }

    protected function tearDown(): void
    {
        // Restore original default locale
        \Locale::setDefault($this->defaultLocale);
        parent::tearDown();
    }

    public function testSynchronizeWithStripeCreateNewPrice(): void
    {
        $product = new class extends Product {
            private ?string $stripeId = 'prod_123';

            public function getStripeId(): ?string
            {
                return $this->stripeId;
            }

            public function setStripeId(?string $id): static
            {
                $this->stripeId = $id;

                return $this;
            }

            public function synchronizeWithStripe(StripeClient $stripe): self
            {
                return $this;
            }

            public function getName(): string
            {
                return 'Test Product';
            }
        };

        $priceEntity = new ProductPriceEntity();
        $priceEntity->setProduct($product);
        $priceEntity->setUnitAmount(10.00);
        $priceEntity->setCurrency('EUR');
        // isEnabled is true by default in Price entity

        $expectedStripePriceId = 'price_new_123';
        $stripePriceObject = new StripeApiPrice($expectedStripePriceId);

        $this->priceServiceMock
            ->expects($this->once())
            ->method('create')
            ->with([
                'unit_amount' => 1000, // in cents
                'currency' => 'EUR',
                'product' => 'prod_123',
                'nickname' => '❌ €10.00 - Test Product', // Adjusted for IntlExtension with en_US locale
            ])
            ->willReturn($stripePriceObject);

        $priceEntity->synchronizeWithStripe($this->stripeClientMock);

        $this->assertSame($expectedStripePriceId, $priceEntity->getStripeId());
    }

    public function testSynchronizeWithStripeCreateNewRecurringPrice(): void
    {
        $product = new class extends Product {
            private ?string $stripeId = 'prod_456';

            public function getStripeId(): ?string
            {
                return $this->stripeId;
            }

            public function setStripeId(?string $id): static
            {
                $this->stripeId = $id;

                return $this;
            }

            public function synchronizeWithStripe(StripeClient $stripe): self
            {
                return $this;
            }

            public function getName(): string
            {
                return 'Recurring Product';
            }
        };

        $priceEntity = new ProductPriceEntity();
        $priceEntity->setProduct($product);
        $priceEntity->setUnitAmount(25.00);
        $priceEntity->setCurrency('USD');
        $priceEntity->setRecurringInterval(RecurringInterval::Month); // Changed from MONTH
        $priceEntity->setRecurringIntervalCount(1);
        $priceEntity->setRecurringTrialPeriodDays(7);

        $expectedStripePriceId = 'price_recurring_456';
        $stripePriceObject = new StripeApiPrice($expectedStripePriceId);

        $this->priceServiceMock
            ->expects($this->once())
            ->method('create')
            ->with([
                'unit_amount' => 2500,
                'currency' => 'USD',
                'product' => 'prod_456',
                'nickname' => '❌ $25.00 / 1 month (7 days trial) - Recurring Product', // Adjusted
                'recurring' => [
                    'interval' => 'month',
                    'interval_count' => 1,
                    'trial_period_days' => 7,
                ],
            ])
            ->willReturn($stripePriceObject);

        $priceEntity->synchronizeWithStripe($this->stripeClientMock);

        $this->assertSame($expectedStripePriceId, $priceEntity->getStripeId());
    }

    public function testSynchronizeWithStripeUpdateExistingPrice(): void
    {
        $product = new class extends Product {
            private ?string $stripeId = 'prod_789';

            public function getStripeId(): ?string
            {
                return $this->stripeId;
            }

            public function setStripeId(?string $id): static
            {
                $this->stripeId = $id;

                return $this;
            }

            public function synchronizeWithStripe(StripeClient $stripe): self
            {
                return $this;
            }

            public function getName(): string
            {
                return 'Update Product';
            }
        };

        $priceEntity = new ProductPriceEntity();
        $priceEntity->setProduct($product);
        $priceEntity->setStripeId('price_existing_789');
        $priceEntity->setUnitAmount(50.00);
        $priceEntity->setCurrency('GBP');
        $priceEntity->setEnabled(false); // This should be updated

        $this->priceServiceMock
            ->expects($this->once())
            ->method('update')
            ->with(
                'price_existing_789',
                [
                    'active' => false,
                    'nickname' => '❌ £50.00 - Update Product', // Adjusted
                ]
            ); // Update does not return the price object in the Stripe PHP library v7, returns void/Price

        $priceEntity->synchronizeWithStripe($this->stripeClientMock);
        $this->assertSame('price_existing_789', $priceEntity->getStripeId());
    }

    public function testSynchronizeWithStripeSynchronizesProductIfNotExistsOnStripe(): void
    {
        $productMock = $this->createMock(Product::class);
        // Initial call to getStripeId returns null
        $productMock->expects($this->atLeastOnce())
            ->method('getStripeId')
            ->willReturnOnConsecutiveCalls(null, 'prod_synced_id_test'); // First null, then the ID

        // synchronizeWithStripe on product should be called
        $productMock->expects($this->once())
            ->method('synchronizeWithStripe')
            ->with($this->stripeClientMock)
            ->willReturnSelf(); // For PHPUnit mock, willReturnSelf is fine.

        $productMock->method('getName')->willReturn('Product To Sync');

        $priceEntity = new ProductPriceEntity();
        $priceEntity->setProduct($productMock);
        $priceEntity->setUnitAmount(5.00);
        $priceEntity->setCurrency('CAD');

        $expectedStripePriceId = 'price_after_prod_sync_123';
        $stripePriceObject = new StripeApiPrice($expectedStripePriceId);

        $this->priceServiceMock
            ->expects($this->once())
            ->method('create')
            ->with([
                'unit_amount' => 500,
                'currency' => 'CAD',
                'product' => 'prod_synced_id_test', // Uses the synced product ID
                'nickname' => '❌ CA$5.00 - Product To Sync', // Adjusted
            ])
            ->willReturn($stripePriceObject);

        $priceEntity->synchronizeWithStripe($this->stripeClientMock);

        $this->assertSame($expectedStripePriceId, $priceEntity->getStripeId());
    }
}
