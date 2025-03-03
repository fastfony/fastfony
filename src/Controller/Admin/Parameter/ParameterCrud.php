<?php

declare(strict_types=1);

namespace App\Controller\Admin\Parameter;

use App\Entity\Parameter\Parameter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Routing\RouterInterface;

class ParameterCrud extends AbstractCrudController
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Parameter::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $keyField = TextField::new('key');
        if (Crud::PAGE_NEW !== $pageName) {
            $keyField->setDisabled();
        }

        return [
            $keyField,
            AssociationField::new('category'),
            TextField::new('label'),
            TextField::new('help'),
            ChoiceField::new('type')
                ->setChoices(array_combine(Parameter::LIST_TYPES, Parameter::LIST_TYPES)),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters
            ->add('key')
            ->add('label')
            ->add('help')
            ->add('category')
        ;

        return $filters;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setEntityLabelInPlural('Parameters')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $categoryCrud = Action::new('categoryCrud', 'Categories', 'fa fa-gear')
            ->linkToUrl($this->router->generate('admin_parameter_category_crud_index'))
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $categoryCrud)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
        ;
    }
}
