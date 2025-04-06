<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    public const FF_VERSION = '0.4.0-DEV';
    public const FF_VERSION_ID = 00400;
    public const FF_MAJOR_VERSION = 0;
    public const FF_MINOR_VERSION = 4;
    public const FF_RELEASE_VERSION = 0;
    public const FF_EXTRA_VERSION = 'DEV';

    public const FF_END_OF_MAINTENANCE = '03/2026';
    public const FF_END_OF_LIFE = '03/2026';
}
