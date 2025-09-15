<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class législationRGPDController extends AbstractController
{
    // Route pour la page sur la législation et le RGPD
    #[Route('/législationRGPD', name: 'app_législationRGPD')]
    public function index(): Response
    {
        // Affiche le template Twig 'législation&rgpd/index.html.twig'
        // et passe une variable 'controller_name' à la vue
        return $this->render('législation&rgpd/index.html.twig', [
            'controller_name' => 'Législation&RGPDController',
        ]);
    }
}
