<?php

declare(strict_types=1);

namespace App\Controller\Admin\Scheduler;

use App\Entity\Scheduler\Run;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RunCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Run::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'run_crud.title')
        ;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('messageContextId'),
            TextField::new('scheduler'),
            DateTimeField::new('runDateFormatted'),
            BooleanField::new('terminated')
                ->renderAsSwitch(false),
            TextField::new('trigger')
                ->hideOnIndex(),
            BooleanField::new('hasFailureOutput')
                ->renderAsSwitch(false),
            TextEditorField::new('input')
                ->setTemplatePath('admin/scheduler/run_crud/fields/input.html.twig')
                ->hideOnIndex(),
            TextEditorField::new('failureOutput')
                ->setTemplatePath('admin/scheduler/run_crud/fields/failure_output.html.twig')
                ->hideOnIndex(),
            DateTimeField::new('createdAt')
                ->hideOnIndex(),
            DateTimeField::new('updatedAt')
                ->hideOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $schedulerIndex = Action::new('schedulerIndex', 'link.scheduler.index')
            ->createAsGlobalAction()
            ->linkToRoute('admin_scheduler_index');

        $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $schedulerIndex)
        ;

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add('messageContextId');

        return $filters;
    }
}
