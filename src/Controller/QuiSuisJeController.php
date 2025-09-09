<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuiSuisJeController extends AbstractController
{
    #[Route('/quisuisje', name: 'app_qui_suis_je')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'QuiSuiJeController',
        ]);
    }
}
