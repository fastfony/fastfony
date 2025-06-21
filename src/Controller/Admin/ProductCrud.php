<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Product\Product;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use App\Repository\Product\ProductRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends AbstractCrudController<Product>
 */
class ProductCrud extends AbstractCrudController
{
    use SeoTabFields;

    public function __construct(
        private readonly FeatureFlag $featureFlag,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly ProductRepository $productRepository,
        private readonly RequestStack $requestStack,
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

        $entityId = $this->requestStack->getCurrentRequest()->attributes->get('entityId');
        $currentProduct = null;
        if (null !== $entityId) {
            $currentProduct = $this->productRepository->find($entityId);
        }

        return [
            FormField::addTab('General'),
            FormField::addColumn(8),
            TextField::new('name'),
            TextField::new('buttonLabel'),
            AssociationField::new('defaultPrice')
                ->setQueryBuilder(
                    static fn (QueryBuilder $queryBuilder) => $queryBuilder
                        ->andWhere('entity.product = :product')
                        ->setParameter('product', $currentProduct)
                )
                ->hideWhenCreating(),
            FormField::addColumn(4)
                ->addCssClass('text-right pt-4 pe-5'),
            BooleanField::new('enabled'),
            FormField::addColumn(12),
            TextField::new('shortDescription'),
            TextareaField::new('description')
                ->hideOnIndex(),
            AssociationField::new('marketingFeatures'),
            FormField::addTab('Prices'),
            CollectionField::new('prices')
                ->useEntryCrudForm(PriceCrud::class)
                ->setHelp('help.prices')
                ->hideOnIndex(),
            ...$this->getSeoTabFields(),
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

    public function configureActions(Actions $actions): Actions
    {
        $marketingFeaturesAction = Action::new('marketingFeatures', 'Features')
            ->linkToUrl(
                $this->adminUrlGenerator
                    ->setController(MarketingFeatureCrud::class)
                    ->setAction(Action::INDEX)
                    ->generateUrl()
            )
            ->createAsGlobalAction()
            // https://github.com/EasyCorp/EasyAdminBundle/issues/6652
            // We use AdminUrlGenerator instead of directly using the route name
//            ->linkToRoute('admin_marketing_feature_crud_index')
        ;

        return $actions
            ->add(Crud::PAGE_INDEX, $marketingFeaturesAction)
            ->update(Crud::PAGE_INDEX, Action::DELETE, static function (Action $action) {
                return $action->displayIf(static function (Product $product) {
                    foreach ($product->getPrices() as $price) {
                        // If have orders, we can't delete the product
                        if ($price->getOrders()->count() > 0) {
                            return false;
                        }
                    }

                    return true;
                });
            })
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE)
        ;
    }
}
