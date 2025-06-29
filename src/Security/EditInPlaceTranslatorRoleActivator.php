<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Translation\Bundle\EditInPlace\ActivatorInterface;

class EditInPlaceTranslatorRoleActivator implements ActivatorInterface
{
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function checkRequest(?Request $request = null): bool
    {
        try {
            // Translator edit in place is not available in the admin area
            if ($request && str_starts_with($request->getPathInfo(), '/admin')) {
                return false;
            }

            return $this->authorizationChecker->isGranted('ROLE_EDIT_IN_PLACE');
        } catch (AuthenticationCredentialsNotFoundException $e) {
            return false;
        }
    }
}
