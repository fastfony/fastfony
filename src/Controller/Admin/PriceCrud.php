<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Product\Price;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PriceCrud extends AbstractCrudController
{
    public function __construct(
        private readonly FeatureFlag $featureFlag,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Price::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        if (!$this->featureFlag->isEnabled(Features::PRODUCTS->value)) {
            throw new NotFoundHttpException();
        }

        return [
            FormField::addColumn(8),
            TextField::new('id')
                ->setDisabled(),
            NumberField::new('unitAmount'),
            CurrencyField::new('currency'),
            BooleanField::new('enabled'),
        ];
    }
}
