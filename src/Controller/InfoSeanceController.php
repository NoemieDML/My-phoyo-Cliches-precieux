<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InfoSeanceController extends AbstractController
{
    #[Route('/info/seance', name: 'app_info_seance')]
    public function index(): Response
    {
        return $this->render('info_seance/index.html.twig', [
            'controller_name' => 'InfoSeanceController',
        ]);
    }
}
