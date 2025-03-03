<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Page\Page;
use App\Entity\Page\PageLogEntry;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminAction;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class PageLogEntryCrud extends AbstractCrudController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly EntityManagerInterface $entityManager,
        private readonly AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return PageLogEntry::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Page Log Entries')
            ->setDefaultSort(['version' => 'DESC'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $referer = $this->requestStack->getCurrentRequest()->headers->get('referer');

        if (null !== $referer && str_contains($referer, 'page-crud')) {
            $backAction = Action::new('back', 'Back to Page')
                ->linkToUrl($referer)
                ->createAsGlobalAction();

            $actions->add(Crud::PAGE_INDEX, $backAction);
        }

        $revertAction = Action::new('revertToPreviousVersion', 'Revert to this version')
            ->linkToCrudAction('revertToPreviousVersion')
        ;

        return $actions
            ->add(Crud::PAGE_INDEX, $revertAction)
            ->disable(Crud::PAGE_NEW, Crud::PAGE_EDIT)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            NumberField::new('version'),
            DateTimeField::new('loggedAt'),
            TextField::new('action'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('objectId')
        ;
    }

    #[AdminAction(routePath: '/revert', routeName: 'revertToPreviousVersion', methods: ['GET'])]
    public function revertToPreviousVersion(AdminContext $context): RedirectResponse
    {
        $pageLogEntry = $context->getEntity()->getInstance();
        $page = $this->entityManager->find(Page::class, $pageLogEntry->getObjectId());
        if ($page) {
            $this->entityManager->getRepository(PageLogEntry::class)->revert($page, $pageLogEntry->getVersion());
            $this->entityManager->flush();
            $this->addFlash('success', 'flash.revert_to_previous_version.success');

            return $this->redirect(
                $this->adminUrlGenerator
                    ->setController(PageCrud::class)
                    ->setAction(Action::EDIT)
                    ->setEntityId($page->getId())
                    ->generateUrl()
            );
        }

        throw new \LogicException('The page could not be found.');
    }
}
