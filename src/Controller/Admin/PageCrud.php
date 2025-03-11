<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Admin\Field\Editorjs;
use App\Admin\Field\Json;
use App\Entity\Page\Page;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class PageCrud extends AbstractCrudController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
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
            ->linkToRoute('page_show', static function (Page $page): array {
                return ['pageSlug' => $page->getSlug()];
            })
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
            FormField::addColumn(8),
            TextField::new('name'),
            FormField::addColumn(4)
                ->addCssClass('text-right pt-4 pe-5'),
            BooleanField::new('enabled'),
            FormField::addColumn(12),
            TextField::new('title')
                ->hideOnIndex(),
            Editorjs::new('content')
                ->setColumns(12)
                ->hideOnIndex()
                ->setHelp('help.content'),
            FormField::addTab('SEO'),
            FormField::addColumn(6),
            BooleanField::new('homepage'),
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
            FormField::addTab('Rich Snippets'),
            Json::new('richSnippets')
                ->setColumns(10)
                ->setHtmlAttribute('rows', 30)
                ->hideOnIndex(),
        ];
    }
}
