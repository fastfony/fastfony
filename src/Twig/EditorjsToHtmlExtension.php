<?php

declare(strict_types=1);

namespace App\Twig;

use Durlecode\EJSParser\Parser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EditorjsToHtmlExtension extends AbstractExtension
{
    /**
     * @return array<TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('editorjs_to_html', [$this, 'editorjsToHtml'], ['is_safe' => ['html']]),
        ];
    }

    public function editorjsToHtml(string $json): string
    {
        return trim(Parser::parse($json)->toHtml());
    }
}
