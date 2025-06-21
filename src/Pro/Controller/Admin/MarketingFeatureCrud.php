<?php

declare(strict_types=1);

namespace App\Pro\Controller\Admin;

use App\Pro\Entity\Product\MarketingFeature;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<MarketingFeature>
 */
class MarketingFeatureCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MarketingFeature::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            BooleanField::new('enabled'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Features')
            ->setDefaultSort(['name' => 'ASC']);
    }
}
