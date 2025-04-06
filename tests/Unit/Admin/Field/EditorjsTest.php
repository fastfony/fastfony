<?php

declare(strict_types=1);

namespace App\Tests\Unit\Admin\Field;

use App\Admin\Field\Editorjs;
use PHPUnit\Framework\TestCase;

class EditorjsTest extends TestCase
{
    public function testNew(): void
    {
        $actual = Editorjs::new('test');
        $this->assertInstanceOf(Editorjs::class, $actual);
    }
}
