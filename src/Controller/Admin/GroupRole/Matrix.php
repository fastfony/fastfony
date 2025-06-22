<?php

declare(strict_types=1);

namespace App\Controller\Admin\GroupRole;

use App\Repository\User\GroupRepository;
use App\Repository\User\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Matrix extends AbstractController
{
    #[Route('/admin/group-role/matrix', name: 'admin_group_role_matrix')]
    public function __invoke(
        GroupRepository $groupRepository,
        RoleRepository $roleRepository,
    ): Response {
        return $this->render(
            'admin/group-role/matrix.html.twig',
            [
                'group_entities' => $groupRepository->findAll(),
                'role_entities' => $roleRepository->findAll(),
            ],
        );
    }
}
