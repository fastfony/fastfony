<?php

declare(strict_types=1);

namespace App\Pro\Entity\Product;

use App\Pro\Repository\Product\ProductLogEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

#[ORM\Entity(repositoryClass: ProductLogEntryRepository::class)]
class ProductLogEntry extends AbstractLogEntry
{
}
