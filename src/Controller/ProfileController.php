<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(PhotoRepository $photoRepository): Response
    {
        // Récupération de toutes les photos en BDD
        $photos = $photoRepository->findAll();

        // On passe "photos" à la vue
        return $this->render('profile/index.html.twig', [
            'photos' => $photos,
        ]);
    }
}
