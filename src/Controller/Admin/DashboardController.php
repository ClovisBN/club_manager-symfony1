<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Entity\Game;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Equipe;

use App\Entity\Section;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Club Manager Symfony1');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Clubs', 'fa fa-building', Club::class);
        yield MenuItem::linkToCrud('Equipes', 'fa fa-users', Equipe::class);
        yield MenuItem::linkToCrud('Roles', 'fa fa-user-shield', Role::class);
        yield MenuItem::linkToCrud('Sections', 'fa fa-layer-group', Section::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Games', 'fa fa-user', Game::class);

    }
}
