<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Taxonomy as TaxonomyEntity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TaxonomyCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TaxonomyEntity::class;
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
            ->overrideTemplate('crud/index', 'admin/taxonomy/index.html.twig');
    }
}
