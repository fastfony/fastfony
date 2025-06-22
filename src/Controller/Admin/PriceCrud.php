<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Product\Price;
use App\Enum\RecurringInterval;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends AbstractCrudController<Price>
 */
class PriceCrud extends AbstractCrudController
{
    public function __construct(
        private readonly FeatureFlag $featureFlag,
        private bool $stripeEnabled,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Price::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        if (!$this->featureFlag->isEnabled(Features::PRODUCTS->value)) {
            throw new NotFoundHttpException();
        }

        $unitAmountField = NumberField::new('unitAmount');
        $currencyField = CurrencyField::new('currency');
        $recurringInterval = ChoiceField::new('recurringInterval')
            ->setFormType(EnumType::class)
            ->setFormTypeOption('class', RecurringInterval::class)
            ->hideOnIndex()
        ;
        $recurringIntervalCount = NumberField::new('recurringIntervalCount')
            ->hideOnIndex()
        ;
        $recurringTrialPeriodDays = NumberField::new('recurringTrialPeriodDays')
            ->setHelp('help.recurring_trial_period_days')
            ->hideOnIndex()
        ;

        if ($this->stripeEnabled && Crud::PAGE_EDIT === $pageName) {
            // The edition of price in the edit page is not possible because it's not possible with API Stripe
            $fields = [
                $unitAmountField,
                $currencyField,
                $recurringInterval,
                $recurringIntervalCount,
                $recurringTrialPeriodDays,
            ];
            foreach ($fields as $field) {
                $field->setHtmlAttribute('readonly', 'readonly');
            }
        }

        return [
            FormField::addColumn(12),
            BooleanField::new('enabled')
                ->addCssClass('text-right pt-4 pe-5'),
            FormField::addColumn(4),
            FormField::addFieldset('Amount'),
            $unitAmountField,
            $currencyField,
            FormField::addColumn(8),
            FormField::addFieldset('Recurring'),
            $recurringInterval,
            $recurringIntervalCount,
            $recurringTrialPeriodDays,
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DETAIL, Action::DELETE)
        ;
    }
}
