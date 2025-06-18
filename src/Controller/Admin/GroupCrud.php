<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User\Group;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends AbstractCrudController<Group>
 */
class GroupCrud extends AbstractCrudController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly FeatureFlag $featureFlag,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Group::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            BooleanField::new('onRegistration'),
            CollectionField::new('roles')
                ->hideOnForm(),
            AssociationField::new('roles')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->hideOnIndex(),
            AssociationField::new('users')
                ->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Groups')
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        if (!$this->featureFlag->isEnabled(Features::USERS_MANAGEMENT->value)) {
            throw new NotFoundHttpException();
        }

        $rolesCrudAction = Action::new('roles', 'Roles', 'fa fa-tags')
            ->linkToUrl(
                $this->adminUrlGenerator
                    ->setController(RoleCrud::class)
                    ->setAction(Action::INDEX)
                    ->generateUrl()
            )
            // https://github.com/EasyCorp/EasyAdminBundle/issues/6652
            // We use AdminUrlGenerator instead of directly using the route name
//            ->linkToRoute(
//                'admin_role_crud_index',
//            )
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $rolesCrudAction)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('roles')
        ;
    }
}
