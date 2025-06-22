<?php

declare(strict_types=1);

namespace App\Controller\Admin\RecordCollection;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Index extends AbstractController
{
    #[Route('/admin/collection', name: 'admin_record_collection_index')]
    public function __invoke(): Response
    {
        return $this->render('admin/record_collection/index.html.twig');
    }
}
