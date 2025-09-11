<?php

// src/Controller/AdminPhotoController.php
namespace App\Controller\Admin;

use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPhotoController extends AbstractController
{
    #[Route('/admin/photos', name: 'admin_photos')]
    public function index(PhotoRepository $photoRepository): Response
    {
        // Récupérer toutes les photos
        $photos = $photoRepository->findAll();

        // Passer les photos au template
        return $this->render('admin_photo/index.html.twig', [
            'photos' => $photos,
        ]);
    }
}
