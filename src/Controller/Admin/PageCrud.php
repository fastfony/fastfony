<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Admin\Field\Json;
use App\Admin\Field\RichTextEditor;
use App\Entity\Page\Page;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class PageCrud extends AbstractCrudController
{
    use SeoTabFields;

    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
        private RouterInterface $router,
        private readonly FeatureFlag $featureFlag,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Pages')
            ->setDefaultSort(['name' => 'ASC'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        if (!$this->featureFlag->isEnabled(Features::PAGES->value)) {
            throw new NotFoundHttpException();
        }

        $viewLogEntriesAction = Action::new('viewLogEntries', 'View Log Entries')
            ->linkToUrl(
                function (Page $entity) {
                    return $this->adminUrlGenerator
                        ->setController(PageLogEntryCrud::class)
                        ->setAction(Action::INDEX)
                        ->set('filters[objectId][comparison]', '=')
                        ->set('filters[objectId][value]', $entity->getId())
                        ->generateUrl();
                }
            )
            // https://github.com/EasyCorp/EasyAdminBundle/issues/6652
            // We use AdminUrlGenerator instead of directly using the route name
//            ->linkToRoute(
//                'admin_page_log_entry_crud_index',
//                function (Page $page): array {
//                    return [
//                        'entity' => 'PageLogEntry',
//                        'filters[objectId][comparison]' => '=',
//                        'filters[objectId][value]' => $page->getId(),
//                        'filters[objectClass][comparison]' => '=',
//                        'filters[objectClass][value]' => $page::class,
//                    ];
//                }
//            )
        ;

        $viewAction = Action::new('view', 'View or preview')
            ->linkToUrl(
                function (Page $entity) {
                    if ($entity->isHomepage()) {
                        return $this->router->generate('homepage');
                    }

                    return $this->router->generate('page_show', ['slug' => $entity->getSlug()]);
                }
            )
            ->setIcon('fa fa-eye')
            ->setHtmlAttributes(['target' => '_blank'])
        ;

        $actions
            ->add(Crud::PAGE_EDIT, $viewLogEntriesAction)
            ->add(Crud::PAGE_EDIT, $viewAction)
            ->add(Crud::PAGE_INDEX, $viewLogEntriesAction)
            ->add(Crud::PAGE_INDEX, $viewAction)
            ->reorder(Crud::PAGE_INDEX, ['view', Crud::PAGE_EDIT, 'viewLogEntries'])
        ;

        return $actions;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Content'),
            FormField::addColumn(6),
            BooleanField::new('enabled')
                ->setHelp('help.enabled')
                ->addCssClass('pt-4 pe-5'),
            FormField::addColumn(6),
            BooleanField::new('published')
                ->setHelp('help.published')
                ->addCssClass('pt-4 pe-5'),
            FormField::addColumn(12),
            TextField::new('name'),
            TextField::new('title')
                ->hideOnIndex(),
            RichTextEditor::new('content')
                ->setColumns(12)
                ->hideOnIndex(),
            ...$this->getSeoTabFields(),
            FormField::addTab('Rich Snippets'),
            Json::new('richSnippets')
                ->setColumns(10)
                ->setHtmlAttribute('rows', 30)
                ->hideOnIndex(),
        ];
    }
}
