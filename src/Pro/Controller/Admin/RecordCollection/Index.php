<?php

declare(strict_types=1);

namespace App\Pro\Controller\Admin\RecordCollection;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Index extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    #[Route('/admin/collection', name: 'admin_record_collection_index')]
    #[Template('pro/admin/record_collection/index.html.twig')]
    public function __invoke()
    {
    }
}
