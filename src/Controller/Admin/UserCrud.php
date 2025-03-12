<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User\User;
use App\Security\LoginLink;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminAction;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserCrud extends AbstractCrudController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Users')
            ->setDefaultSort(['email' => 'ASC']);
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            CollectionField::new('groups')
                ->hideOnForm(),
            AssociationField::new('groups')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->hideOnIndex(),
            BooleanField::new('enabled'),
            AssociationField::new('profile')
                ->renderAsEmbeddedForm()
                ->hideOnIndex(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('email')
            ->add('enabled')
            ->add('groups')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $sendLoginLinkAction = Action::new('sendLoginLinkEmail', 'user_crud.action.send_login_link_email')
            ->linkToCrudAction('sendLoginLinkEmail')
            ->displayIf(static fn ($entity) => $entity->isEnabled());

        $groupsCrudAction = Action::new('groups', 'Groups')
            ->linkToUrl(
                $this->adminUrlGenerator
                    ->setController(GroupCrud::class)
                    ->setAction(Action::INDEX)
                    ->generateUrl()
            )
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_EDIT, $sendLoginLinkAction)
            ->add(Crud::PAGE_INDEX, $sendLoginLinkAction)
            ->add(Crud::PAGE_INDEX, $groupsCrudAction)
        ;
    }

    #[AdminAction(routePath: '/{entityId}/send-login-link', routeName: 'send_login_link')]
    public function sendLoginLinkEmail(
        AdminContext $adminContext,
        LoginLink $loginLink,
    ): RedirectResponse {
        $user = $adminContext->getEntity()->getInstance();

        if (null !== $user) {
            if ($loginLink->send($user)) {
                $this->addFlash(
                    'success',
                    'flash.login_link_sent.success'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'flash.login_link_sent.error',
                );
            }
        }

        return $this->redirectToRoute('admin_user_crud_index');
    }
}
