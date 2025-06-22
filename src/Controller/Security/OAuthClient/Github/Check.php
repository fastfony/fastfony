<?php

declare(strict_types=1);

namespace App\Controller\Security\OAuthClient\Github;

use App\Controller\Security\OAuthClient\AbstractCheck;
use Symfony\Component\Routing\Attribute\Route;

/** @phpstan-ignore symfony.noClassLevelRoute */
#[Route('/connect/github/check', name: 'connect_github_check')]
class Check extends AbstractCheck
{
}
