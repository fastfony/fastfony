<?php

declare(strict_types=1);

namespace App\Controller\Admin\Parameter;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Index extends AbstractController
{
    #[Route('/admin/parameters', name: 'admin_parameters')]
    public function __invoke(
    ): Response {
        /* See assets/vue/controllers/Parameters.vue */
        return $this->render('admin/parameters/index.html.twig');
    }
}
