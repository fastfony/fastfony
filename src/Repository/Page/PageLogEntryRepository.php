<?php

declare(strict_types=1);

namespace App\Repository\Page;

use App\Repository\SaveAndRemoveMethod;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;

class PageLogEntryRepository extends LogEntryRepository
{
    use SaveAndRemoveMethod;
}
