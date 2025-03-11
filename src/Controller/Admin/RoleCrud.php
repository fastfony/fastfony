<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User\Role;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class RoleCrud extends AbstractCrudController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Role::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('description'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $groupsCrudAction = Action::new('groups', 'Groups')
            ->linkToUrl(
                $this->adminUrlGenerator
                    ->setController(GroupCrud::class)
                    ->setAction(Action::INDEX)
                    ->generateUrl()
            )
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $groupsCrudAction)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
        ;
    }
}
