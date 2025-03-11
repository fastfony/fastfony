<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Page\Page;
use App\Entity\Parameter\Parameter;
use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard as EasyAdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class Dashboard extends AbstractDashboardController
{
    public function __construct(
        private string $companyName,
    ) {
    }

    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(UserCrud::class)->generateUrl());
    }

    public function configureDashboard(): EasyAdminDashboard
    {
        return EasyAdminDashboard::new()
            ->setTitle($this->companyName)
            ->setFaviconPath('favicon.ico')
            ->setTranslationDomain('admin')
        ;
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setFormThemes(['form/custom_types.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addWebpackEncoreEntry('easyadmin')
        ;
    }

    /**
     * @return iterable<MenuItemInterface>
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('menu.dashboard', 'fa fa-home');
        yield MenuItem::section('menu.crud');
        yield MenuItem::linkToCrud('menu.crud.users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('menu.crud.pages', 'fas fa-file', Page::class);
        yield MenuItem::linkToCrud('menu.crud.parameters', 'fas fa-gears', Parameter::class);
        yield MenuItem::section('---');
        yield MenuItem::linkToRoute('menu.settings', 'fas fa-gear', 'admin_parameters');
        yield MenuItem::linkToRoute('menu.exit', 'fas fa-door-open', 'homepage');
    }
}
