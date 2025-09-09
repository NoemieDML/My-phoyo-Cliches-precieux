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
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer uniquement les photos de cet utilisateur
        $photos = $photoRepository->findBy(['user' => $user]);

        return $this->render('profile/index.html.twig', [
            'photos' => $photos,
        ]);
    }
}
