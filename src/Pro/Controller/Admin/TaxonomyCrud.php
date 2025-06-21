<?php

declare(strict_types=1);

namespace App\Pro\Controller\Admin;

use App\Pro\Entity\Taxonomy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<Taxonomy>
 */
class TaxonomyCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Taxonomy::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('parent'),
            TextField::new('key'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->overrideTemplate('crud/index', 'pro/admin/taxonomy/index.html.twig');
    }
}
