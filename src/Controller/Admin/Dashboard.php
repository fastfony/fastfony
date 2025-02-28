<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Entity\Parameter;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard as EasyAdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
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

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Pages', 'fas fa-file', Page::class);
        yield MenuItem::linkToCrud('Parameters', 'fas fa-gears', Parameter::class);
        yield MenuItem::linkToRoute('Settings', 'fas fa-gear', 'admin_parameters');
        yield MenuItem::linkToRoute('Exit', 'fas fa-door-open', 'homepage');
    }
}
