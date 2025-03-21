<?php

declare(strict_types=1);

namespace App\Entity\Page;

use App\Repository\Page\PageLogEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

#[ORM\Entity(repositoryClass: PageLogEntryRepository::class)]
class PageLogEntry extends AbstractLogEntry
{
}
