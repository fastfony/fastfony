<?php

declare(strict_types=1);

namespace App\Controller\Admin\GroupRole;

use App\Repository\User\GroupRepository;
use App\Repository\User\RoleRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Matrix extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    #[Route('/admin/group-role/matrix', name: 'admin_group_role_matrix')]
    #[Template('admin/group-role/matrix.html.twig')]
    public function __invoke(
        GroupRepository $groupRepository,
        RoleRepository $roleRepository,
    ): array {
        return [
            'group_entities' => $groupRepository->findAll(),
            'role_entities' => $roleRepository->findAll(),
        ];
    }
}
