<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuiSuisJeController extends AbstractController
{
    // Route pour la page "Qui suis-je"
    #[Route('/quisuisje', name: 'app_qui_suis_je')]
    public function index(): Response
    {
        // Affiche le template Twig 'home/index.html.twig'
        // Passe une variable 'controller_name' à la vue
        return $this->render('home/index.html.twig', [
            'controller_name' => 'QuiSuisJeController',
        ]);
    }
}
