<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Repository\Product\ProductLogEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

#[ORM\Entity(repositoryClass: ProductLogEntryRepository::class)]
class ProductLogEntry extends AbstractLogEntry
{
}
