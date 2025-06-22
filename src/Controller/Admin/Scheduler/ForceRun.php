<?php

declare(strict_types=1);

namespace App\Controller\Admin\Scheduler;

use App\Scheduler\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class ForceRun extends AbstractController
{
    public function __construct(
        private readonly Handler $handler,
    ) {
    }

    #[Route('/admin/scheduler/force-run/{recurringMessageId}', name: 'admin_scheduler_force_run')]
    public function __invoke(string $recurringMessageId): RedirectResponse
    {
        if ($this->handler->forceRun($recurringMessageId)) {
            $this->addFlash('success', 'flash.scheduler.force_run.success');
        } else {
            $this->addFlash('danger', 'flash.scheduler.force_run.error');
        }

        return $this->redirectToRoute('admin', ['routeName' => 'admin_scheduler_index']);
    }
}
