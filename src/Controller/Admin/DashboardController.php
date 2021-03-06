<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Netflixandchill');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Genre', 'fas fa-list', Genre::class);
        yield MenuItem::linkToCrud('Movie', 'fas fa-film', Movie::class);
        yield MenuItem::linkToCrud('Order', 'fa fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Product', 'icon class', Product::class);
    }
}
