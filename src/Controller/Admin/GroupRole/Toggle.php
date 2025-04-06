<?php

declare(strict_types=1);

namespace App\Controller\Admin\GroupRole;

use App\Repository\User\GroupRepository;
use App\Repository\User\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;

class Toggle extends AbstractController
{
    public function __construct(
        private readonly GroupRepository $groupRepository,
        private readonly RoleRepository $roleRepository,
        private readonly Security $security,
    ) {
    }

    #[Route('/admin/group_role/toggle', name: 'admin_group_role_toggle', methods: ['POST'])]
    public function __invoke(
        Request $request,
    ): Response {
        $request = $request->toArray();
        $group = $this->groupRepository->find($request['group_id']);
        $role = $this->roleRepository->find($request['role_id']);

        if ($group->getRoles()->contains($role)) {
            $group->removeRole($role);
            $checked = false;
        } else {
            $group->addRole($role);
            $checked = true;
        }

        $this->groupRepository->save($group);

        $this->security->login(
            $this->getUser(),
            'security.authenticator.remember_me.main',
            'main',
            [(new RememberMeBadge())->enable()],
        );

        return $this->json([
            'checked' => $checked,
        ]);
    }
}
