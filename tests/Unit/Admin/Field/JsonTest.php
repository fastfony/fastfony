<?php

declare(strict_types=1);

namespace App\Tests\Unit\Admin\Field;

use App\Admin\Field\Json;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testNew(): void
    {
        $actual = Json::new('test');
        $this->assertInstanceOf(Json::class, $actual);
    }
}
