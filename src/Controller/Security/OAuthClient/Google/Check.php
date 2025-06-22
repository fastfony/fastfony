<?php

declare(strict_types=1);

namespace App\Controller\Security\OAuthClient\Google;

use App\Controller\Security\OAuthClient\AbstractCheck;
use Symfony\Component\Routing\Attribute\Route;

/** @phpstan-ignore symfony.noClassLevelRoute */
#[Route('/connect/google/check', name: 'connect_google_check')]
class Check extends AbstractCheck
{
}
