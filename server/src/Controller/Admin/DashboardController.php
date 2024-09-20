<?php

namespace App\Controller\Admin;

use App\Entity\Adresses;
use App\Entity\Avis;
use App\Entity\Commandes;
use App\Entity\DeliveryMethod;
use App\Entity\Evenement;
use App\Entity\Paiement;
use App\Entity\Panier;
use App\Entity\Pays;
use App\Entity\Produit;
use App\Entity\Promotion;
use App\Entity\Theme;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/tools', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        
        return $this->render('admin/dash.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin Tools');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Paniers', 'fas fa-shopping-cart', Panier::class);
        yield MenuItem::linkToCrud('Paiements', 'fas fa-credit-card', Paiement::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-dolly', Commandes::class);
        yield MenuItem::linkToCrud('Adresses', 'fas fa-location-dot', Adresses::class);
        yield MenuItem::linkToCrud('Méthode de livraison', 'fas fa-truck', DeliveryMethod::class);
        yield MenuItem::linkToCrud('Pays', 'fas fa-earth-europe', Pays::class);
        yield MenuItem::linkToCrud('Theme', 'fas fa-list', Theme::class);
        yield MenuItem::linkToCrud('Produit', 'fas fa-list', Produit::class);
        yield MenuItem::linkToCrud('Avis', 'fas fa-star', Avis::class);
        yield MenuItem::linkToCrud('Promotions', 'fas fa-percent', Promotion::class);
        yield MenuItem::linkToCrud('Evenements', 'fas fa-calendar', Evenement::class);
    }
}
