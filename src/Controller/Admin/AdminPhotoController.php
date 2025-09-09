<?php

namespace App\Controller\Admin;

use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/photos')]
#[IsGranted('ROLE_ADMIN')]
class AdminPhotoController extends AbstractController
{
    #[Route('/', name: 'admin_photos_index')]
    public function index(Request $request, PhotoRepository $photoRepository, EntityManagerInterface $em): Response
    {
        // Récupération de toutes les photos
        $photos = $photoRepository->findAll();

        // Création du formulaire d'ajout de photo
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Attribution de l'utilisateur sélectionné dans le formulaire
            $user = $form->get('user')->getData();
            $photo->setUser($user);

            // Gestion de l'image uploadée
            $file = $form->get('image_path')->getData();
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('photos_directory'), $filename);
                $photo->setImagePath($filename);
            }

            // Date de création
            $photo->setCreatedAt(new \DateTime());

            $em->persist($photo);
            $em->flush();

            $this->addFlash('success', 'Photo ajoutée avec succès !');

            return $this->redirectToRoute('admin_photos_index');
        }

        return $this->render('admin_photo/index.html.twig', [
            'photos' => $photos,
            'form' => $form->createView(), // ✅ Obligatoire : passer un FormView à Twig
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_photos_delete')]
    public function delete(Photo $photo, EntityManagerInterface $em): Response
    {
        $em->remove($photo);
        $em->flush();

        $this->addFlash('success', 'Photo supprimée avec succès !');
        return $this->redirectToRoute('admin_photos_index');
    }
}
