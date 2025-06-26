<?php

declare(strict_types=1);

namespace App\Repository\Product;

use App\Entity\Product\ProductLogEntry;
use App\Repository\SaveAndRemoveMethod;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;

/**
 * @extends LogEntryRepository<ProductLogEntry>
 */
class ProductLogEntryRepository extends LogEntryRepository
{
    use SaveAndRemoveMethod;
}
