<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Validator\UniqueHomepage;
use PHPUnit\Framework\TestCase;

final class UniqueHomepageTest extends TestCase
{
    public function testGetTargets(): void
    {
        $uniqueHomepage = new UniqueHomepage();
        $this->assertSame('class', $uniqueHomepage->getTargets());
    }
}
