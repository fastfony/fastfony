<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User as UserEntity;
use App\Security\LoginLink;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminAction;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserEntity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Users')
            ->setDefaultSort(['email' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnIndex()
                ->hideOnForm(),
            EmailField::new('email'),
            //            CollectionField::new('groups')
            //                ->hideOnForm(),
            //            AssociationField::new('groups')
            //                ->setFormTypeOptions([
            //                    'by_reference' => false,
            //                ])
            //                ->hideOnIndex(),
            BooleanField::new('enabled'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('email')
            ->add('enabled')
//            ->add('groups')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $sendLoginLinkAction = Action::new('sendLoginLinkEmail', 'user_crud.action.send_login_link_email')
            ->linkToCrudAction('sendLoginLinkEmail')
            ->displayIf(fn ($entity) => $entity->isEnabled());

        return $actions
            ->add(Crud::PAGE_EDIT, $sendLoginLinkAction)
            ->add(Crud::PAGE_INDEX, $sendLoginLinkAction);
    }

    #[AdminAction(routePath: '/send-login-link', routeName: 'send_login_link')]
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
