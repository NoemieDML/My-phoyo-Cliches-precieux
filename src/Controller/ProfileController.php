<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    // Page profil, affiche les photos de l'utilisateur connecté
    #[Route('/profile', name: 'app_profile')]
    public function index(PhotoRepository $photoRepository): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        $photos = $photoRepository->findBy(['user' => $user]); // Récupère ses photos

        return $this->render('profile/index.html.twig', [
            'photos' => $photos, // Passe les photos à la vue
        ]);
    }

    // Téléchargement d'une photo depuis l'espace client
    #[Route('/profile/download', name: 'profile_photos_download', methods: ['POST'])]
    public function download(Request $request, PhotoRepository $photoRepository): Response
    {
        $token = $request->request->get('_token'); // CSRF token pour protéger le formulaire
        $photoId = $request->request->get('photo_id'); // ID de la photo à télécharger

        // Vérifie le token et l'ID
        if (!$this->isCsrfTokenValid('photos_download', $token) || !$photoId) {
            $this->addFlash('error', 'Sélection invalide.');
            return $this->redirectToRoute('app_profile');
        }

        $photo = $photoRepository->find($photoId); // Cherche la photo en base

        if (!$photo) { // Si la photo n'existe pas
            $this->addFlash('error', 'Photo introuvable.');
            return $this->redirectToRoute('app_profile');
        }

        $filePath = $this->getParameter('photos_directory') . '/' . $photo->getImage(); // Chemin du fichier

        if (!file_exists($filePath)) { // Vérifie que le fichier existe
            $this->addFlash('error', 'Fichier introuvable.');
            return $this->redirectToRoute('app_profile');
        }

        // Retourne le fichier pour téléchargement
        return $this->file(
            $filePath,
            $photo->getName() . '.' . pathinfo($filePath, PATHINFO_EXTENSION),
            ResponseHeaderBag::DISPOSITION_ATTACHMENT
        );
    }
}
