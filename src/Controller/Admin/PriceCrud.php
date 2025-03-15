<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Product\Price;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PriceCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Price::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addColumn(8),
            TextField::new('id')
                ->setDisabled(true),
            NumberField::new('unitAmount'),
            CurrencyField::new('currency'),
            BooleanField::new('enabled'),
        ];
    }
}
