<?php

declare(strict_types=1);

namespace App\Controller\Admin\Parameter;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/parameters', name: 'admin_parameters')]
#[Template('admin/parameters/index.html.twig')]
class Index extends AbstractController
{
    public function __invoke(
    ) {
        /* See assets/vue/controllers/Parameters.vue */
        return [];
    }
}
