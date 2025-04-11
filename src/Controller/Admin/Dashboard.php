<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Page\Page;
use App\Entity\Parameter\Parameter;
use App\Entity\User\User;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use App\Pro\Entity\OAuth2Server\Client;
use App\Pro\Entity\Order;
use App\Pro\Entity\Product\Product;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard as EasyAdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class Dashboard extends AbstractDashboardController
{
    public function __construct(
        private readonly string $appName,
        private readonly FeatureFlag $featureFlag,
    ) {
    }

    public function index(): Response
    {
        return $this->render('admin/dashboard/show.html.twig');
    }

    public function configureDashboard(): EasyAdminDashboard
    {
        return EasyAdminDashboard::new()
            ->setTitle($this->appName)
            ->setFaviconPath('favicon.ico')
            ->setTranslationDomain('admin')
        ;
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->showEntityActionsInlined()
            ->setFormThemes(['form/custom_types.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addWebpackEncoreEntry('admin')
        ;
    }

    /**
     * @return iterable<MenuItemInterface>
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('menu.dashboard', 'fa fa-home');

        yield MenuItem::section('menu.objects');

        yield MenuItem::linkToRoute('menu.collections', 'fas fa-database', 'admin_record_collection_index');

        if ($this->featureFlag->isEnabled(Features::PAGES->value)) {
            yield MenuItem::linkToCrud('menu.crud.pages', 'fas fa-file', Page::class);
        }

        if ($this->featureFlag->isEnabled(Features::PRODUCTS->value)) {
            yield MenuItem::linkToCrud('menu.crud.products', 'fas fa-cubes', Product::class);
            yield MenuItem::linkToCrud('menu.crud.orders', 'fas fa-cart-arrow-down', Order::class);
        }

        yield MenuItem::linkToCrud('menu.crud.parameters', 'fas fa-gears', Parameter::class);

        if ($this->featureFlag->isEnabled(Features::USERS_MANAGEMENT->value)) {
            yield MenuItem::section('menu.users');
            yield MenuItem::linkToCrud('menu.crud.users', 'fas fa-users', User::class);
            yield MenuItem::linkToRoute('menu.group_role_matrix', 'fas fa-table', 'admin_group_role_matrix');
        }

        if ($this->featureFlag->isEnabled(Features::OAUTH2_SERVER->value)) {
            yield MenuItem::section('menu.oauth');
            yield MenuItem::linkToCrud('menu.oauth.clients', 'fas fa-server', Client::class);
        }

        yield MenuItem::section('---');
        yield MenuItem::linkToRoute('menu.scheduler', 'fas fa-calendar', 'admin_scheduler_index');
        yield MenuItem::linkToRoute('menu.features', 'fas fa-toggle-on', 'admin_features');
        yield MenuItem::linkToRoute('menu.settings', 'fas fa-gear', 'admin_parameters');
        yield MenuItem::linkToRoute('menu.exit', 'fas fa-door-open', 'homepage');
    }
}
