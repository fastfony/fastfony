<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User\RoleCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class RoleCategoryCrud extends AbstractCrudController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return RoleCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Role categories')
            ->setDefaultSort(['name' => 'ASC']);
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            CollectionField::new('roles')
                ->hideOnForm(),
            AssociationField::new('roles')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->hideOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $rolesCrudAction = Action::new('roles', 'Roles', 'fa fa-tags')
            ->linkToUrl(
                $this->adminUrlGenerator
                    ->setController(RoleCrud::class)
                    ->setAction(Action::INDEX)
                    ->generateUrl()
            )
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $rolesCrudAction)
        ;
    }
}
