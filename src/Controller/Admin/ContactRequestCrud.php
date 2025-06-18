<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ContactRequest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<ContactRequest>
 */
class ContactRequestCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactRequest::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName'),
            TextField::new('lastName'),
            AssociationField::new('user'),
            TelephoneField::new('phoneNumber'),
            TextEditorField::new('message'),
            DateTimeField::new('createdAt'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW, Action::EDIT)
        ;

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('firstName')
            ->add('lastName')
            ->add('user')
            ->add('phoneNumber')
            ->add('createdAt');
    }
}
