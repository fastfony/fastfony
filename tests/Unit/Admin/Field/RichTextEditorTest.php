<?php

declare(strict_types=1);

namespace App\Tests\Unit\Admin\Field;

use App\Admin\Field\RichTextEditor;
use PHPUnit\Framework\TestCase;

final class RichTextEditorTest extends TestCase
{
    public function testNew(): void
    {
        $actual = RichTextEditor::new('test');
        $this->assertInstanceOf(RichTextEditor::class, $actual);
    }
}
