<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    public const FF_VERSION = '0.4.3';
    public const FF_EXTRA_VERSION = '';

    public const FF_END_OF_MAINTENANCE = '01/2029';
    public const FF_END_OF_LIFE = '01/2030';
}
