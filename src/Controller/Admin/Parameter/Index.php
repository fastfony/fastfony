<?php

declare(strict_types=1);

namespace App\Controller\Admin\Parameter;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Index extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    #[Route('/admin/parameters', name: 'admin_parameters')]
    #[Template('admin/parameters/index.html.twig')]
    public function __invoke(
    ): array {
        /* See assets/vue/controllers/Parameters.vue */
        return [];
    }
}
