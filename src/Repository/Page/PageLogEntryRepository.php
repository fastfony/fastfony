<?php

declare(strict_types=1);

namespace App\Repository\Page;

use App\Entity\Page\Page;
use App\Repository\SaveAndRemoveMethod;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;

/**
 * @extends LogEntryRepository<Page>
 */
class PageLogEntryRepository extends LogEntryRepository
{
    use SaveAndRemoveMethod;
}
