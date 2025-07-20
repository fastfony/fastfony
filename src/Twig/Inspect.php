<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Attribute\AsTwigFilter;

class Inspect
{
    #[AsTwigFilter('inspect')]
    public function inspect(mixed $value): string
    {
        // We do this because dump() filter is disabled with APP_DEBUG=false
        return print_r($value, true);
    }
}
