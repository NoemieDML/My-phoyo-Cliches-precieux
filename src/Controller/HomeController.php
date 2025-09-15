<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    // Route pour la page d'accueil du site
    #[Route('/', name: 'Accueil')]
    public function index(): Response
    {
        // Affiche le template Twig 'home/index.html.twig'
        // et passe une variable 'controller_name' à la vue
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
