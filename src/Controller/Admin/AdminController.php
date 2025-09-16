<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Route principale pour l'administration
#[Route('/admin')]

// Seuls les admins peuvent accéder à ce contrôleur
#[IsGranted('ROLE_ADMIN')]

class AdminController extends AbstractController
{
    // Page d'accueil de l'administration
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        // Affiche la page admin/index.html.twig
        // Passe la variable 'controller_name' à la vue
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminListController',
        ]);
    }
}
