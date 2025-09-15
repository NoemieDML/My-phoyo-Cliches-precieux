<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InfoSeanceController extends AbstractController
{
    // Route pour la page d'informations sur les séances
    #[Route('/info/seance', name: 'app_info_seance')]
    public function index(): Response
    {
        // Affiche le template Twig 'info_seance/index.html.twig'
        // et passe une variable 'controller_name' à la vue
        return $this->render('info_seance/index.html.twig', [
            'controller_name' => 'InfoSeanceController',
        ]);
    }
}
