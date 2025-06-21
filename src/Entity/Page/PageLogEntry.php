<?php

declare(strict_types=1);

namespace App\Entity\Page;

use App\Repository\Page\PageLogEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

/**
 * @extends AbstractLogEntry<Page>
 */
#[ORM\Entity(repositoryClass: PageLogEntryRepository::class)]
class PageLogEntry extends AbstractLogEntry
{
}
