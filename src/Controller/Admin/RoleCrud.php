<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User\Role;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleCrud extends AbstractCrudController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly FeatureFlag $featureFlag,
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
            AssociationField::new('category'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        if (!$this->featureFlag->isEnabled(Features::USERS_MANAGEMENT->value)) {
            throw new NotFoundHttpException();
        }

        $groupsCrudAction = Action::new('groups', 'Groups', 'fa fa-users')
            ->linkToUrl(
                $this->adminUrlGenerator
                    ->setController(GroupCrud::class)
                    ->setAction(Action::INDEX)
                    ->generateUrl()
            )
            // https://github.com/EasyCorp/EasyAdminBundle/issues/6652
            // We use AdminUrlGenerator instead of directly using the route name
//            ->linkToRoute(
//                'admin_group_crud_index',
//            )
            ->createAsGlobalAction();

        $roleCategoriesCrudAction = Action::new('roleCategories', 'Categories', 'fa fa-gear')
            ->linkToUrl(
                $this->adminUrlGenerator
                    ->setController(RoleCategoryCrud::class)
                    ->setAction(Action::INDEX)
                    ->generateUrl()
            )
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $groupsCrudAction)
            ->add(Crud::PAGE_INDEX, $roleCategoriesCrudAction)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
        ;
    }
}
