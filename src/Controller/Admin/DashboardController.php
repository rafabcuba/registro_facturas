<?php

namespace App\Controller\Admin;

use App\Entity\Factura;
use App\Entity\OrganizationalUnit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administración Control de Facturas')
            ->setFaviconPath('images/favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        // yield MenuItem::linkToUrl('Visit public website', null, '/');
        // yield MenuItem::linkToUrl('Search in Google', 'fab fa-google', 'https://google.com');
        return [
            MenuItem::linktoRoute('Regresar a Control de Facturas', 'fas fa-home', 'home'),
            // MenuItem::section('Nomencladores'),
            // MenuItem::linkToCrud('Unidades Organizativas', 'fa fa-building-o', OrganizationalUnit::class),
            // MenuItem::section('Configuración'),
            // MenuItem::linkToCrud('Sitios', 'fas fa-globe', Site::class),
            MenuItem::section('Datos'),
            MenuItem::linkToCrud('Facturas', 'fas fa-globe', Factura::class),
            MenuItem::section('Seguridad'),
            MenuItem::linkToCrud('Usuarios', 'fas fa-users', User::class),
        ];
    }
}
