<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Attribute\Route;
use Zenstruck\Messenger\Monitor\Controller\MessengerMonitorController as BaseMessengerMonitorController;

/** @phpstan-ignore symfony.noClassLevelRoute */
#[Route('/admin/messenger')]
class MessengerMonitorController extends BaseMessengerMonitorController
{
}
