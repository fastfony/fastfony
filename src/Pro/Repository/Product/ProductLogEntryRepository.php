<?php

declare(strict_types=1);

namespace App\Pro\Repository\Product;

use App\Repository\SaveAndRemoveMethod;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;

class ProductLogEntryRepository extends LogEntryRepository
{
    use SaveAndRemoveMethod;
}
