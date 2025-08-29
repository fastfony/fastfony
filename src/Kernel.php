<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    public const FF_VERSION = '0.3.3';
    public const FF_VERSION_ID = 00303;
    public const FF_MAJOR_VERSION = 0;
    public const FF_MINOR_VERSION = 3;
    public const FF_RELEASE_VERSION = 3;
    public const FF_EXTRA_VERSION = '';

    public const FF_END_OF_MAINTENANCE = '03/2026';
    public const FF_END_OF_LIFE = '03/2026';
}
