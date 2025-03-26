<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Product\Product;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductCrud extends AbstractCrudController
{
    public function __construct(
        private readonly FeatureFlag $featureFlag,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        if (!$this->featureFlag->isEnabled(Features::PRODUCTS->value)) {
            throw new NotFoundHttpException();
        }

        return [
            FormField::addTab('General'),
            FormField::addColumn(8),
            TextField::new('name'),
            TextField::new('buttonLabel'),
            FormField::addColumn(4)
                ->addCssClass('text-right pt-4 pe-5'),
            BooleanField::new('enabled'),
            FormField::addColumn(12),
            TextField::new('shortDescription'),
            TextareaField::new('description')
                ->hideOnIndex(),
            FormField::addTab('Prices'),
            CollectionField::new('prices')
                ->useEntryCrudForm(PriceCrud::class)
                ->setHelp('help.prices'),
            FormField::addTab('SEO'),
            FormField::addColumn(6),
            TextField::new('slug'),
            FormField::addFieldset('Meta tags'),
            TextField::new('metaTitle')
                ->hideOnIndex(),
            TextareaField::new('metaDescription')
                ->hideOnIndex(),
            FormField::addColumn(6),
            FormField::addFieldset('Meta robots')
                ->setHelp('help.meta_robots'),
            BooleanField::new('noindex')
                ->setHelp('help.noindex')
                ->hideOnIndex(),
            BooleanField::new('nofollow')
                ->setHelp('help.nofollow')
                ->hideOnIndex(),
            BooleanField::new('noarchive')
                ->setHelp('help.noarchive')
                ->hideOnIndex(),
            BooleanField::new('nosnippet')
                ->setHelp('help.nosnippet')
                ->hideOnIndex(),
            BooleanField::new('notranslate')
                ->setHelp('help.notranslate')
                ->hideOnIndex(),
            BooleanField::new('noimageindex')
                ->setHelp('help.noimageindex')
                ->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Products')
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('name')
            ->add('enabled')
        ;
    }
}
