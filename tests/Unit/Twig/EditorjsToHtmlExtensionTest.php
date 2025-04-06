<?php

declare(strict_types=1);

namespace App\Tests\Unit\Twig;

use App\Twig\EditorjsToHtmlExtension;
use PHPUnit\Framework\TestCase;

class EditorjsToHtmlExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $this->assertCount(1, (new EditorjsToHtmlExtension())->getFunctions());
    }

    public function testEditorjsToHtml(): void
    {
        $editorjsToHtmlExtension = new EditorjsToHtmlExtension();
        $this->assertSame(
            '<h2 class="prs-header">Header</h2><p class="prs-paragraph">Paragraph</p>',
            $editorjsToHtmlExtension->editorjsToHtml('{"blocks":[{"type":"header","data":{"text":"Header","level":2}},{"type":"paragraph","data":{"text":"Paragraph"}}]}')
        );
    }
}
