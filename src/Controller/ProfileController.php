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
    #[Route('/profile', name: 'app_profile')]
    public function index(PhotoRepository $photoRepository): Response
    {
        $user = $this->getUser();
        $photos = $photoRepository->findBy(['user' => $user]);

        return $this->render('profile/index.html.twig', [
            'photos' => $photos,
        ]);
    }

    #[Route('/profile/download', name: 'profile_photos_download', methods: ['POST'])]
    public function download(Request $request, PhotoRepository $photoRepository): Response
    {
        $token = $request->request->get('_token');
        $photoId = $request->request->get('photo_id'); // On ne gère plus plusieurs IDs

        if (!$this->isCsrfTokenValid('photos_download', $token) || !$photoId) {
            $this->addFlash('error', 'Sélection invalide.');
            return $this->redirectToRoute('app_profile');
        }

        $photo = $photoRepository->find($photoId);

        if (!$photo) {
            $this->addFlash('error', 'Photo introuvable.');
            return $this->redirectToRoute('app_profile');
        }

        $filePath = $this->getParameter('photos_directory') . '/' . $photo->getImage();

        if (!file_exists($filePath)) {
            $this->addFlash('error', 'Fichier introuvable.');
            return $this->redirectToRoute('app_profile');
        }

        return $this->file(
            $filePath,
            $photo->getName() . '.' . pathinfo($filePath, PATHINFO_EXTENSION),
            ResponseHeaderBag::DISPOSITION_ATTACHMENT
        );
    }
}
