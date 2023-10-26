<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $generator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($generator->setController(BookCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Grokhotov Test Task')
            ->renderSidebarMinimized()
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('Books'),
            MenuItem::linkToCrud('Books', 'fa fa-book', Book::class),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
        ];
    }
}
