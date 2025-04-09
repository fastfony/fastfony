<?php

declare(strict_types=1);

namespace App\Pro\Controller\Admin;

use App\Pro\Entity\Order;
use App\Pro\Enum\Stripe\CheckoutSessionStatus;
use App\Pro\Enum\Stripe\PaymentStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class OrderCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('General'),
            IdField::new('id')
                ->hideOnDetail(),
            AssociationField::new('customer'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt')
                ->hideOnIndex(),
            AssociationField::new('price'),
            FormField::addTab('Stripe'),
            FormField::addColumn(6),
            ArrayField::new('stripeCustomerDetails')
                ->setTemplatePath('pro/admin/order/customer_details.html.twig')
                ->hideOnIndex(),
            FormField::addColumn(6),
            TextField::new('stripeId')
                ->hideOnIndex(),
            MoneyField::new('stripeAmountSubtotal')
                ->setCurrencyPropertyPath('price.currency')
                ->setStoredAsCents()
                ->hideOnIndex(),
            MoneyField::new('stripeAmountTotal')
                ->setCurrencyPropertyPath('price.currency')
                ->setStoredAsCents(),
            TextField::new('stripePaymentIntentId')
                ->hideOnIndex(),
            ChoiceField::new('stripePaymentStatus')
                ->setFormType(EnumType::class)
                ->setFormTypeOption('class', PaymentStatus::class),
            ChoiceField::new('stripeSessionStatus')
                ->setFormType(EnumType::class)
                ->setFormTypeOption('class', CheckoutSessionStatus::class)
                ->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Orders')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('customer')
            ->add('stripeId')
            ->add('stripePaymentIntentId')
            ->add('stripePaymentStatus')
            ->add('stripeSessionStatus')
            ->add('stripeAmountSubtotal')
            ->add('stripeAmountTotal')
            ->add('price')
        ;
    }
}
