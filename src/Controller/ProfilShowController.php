<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class ProfilShowController extends AbstractController
{
    #[Route('/show/{id}', name: 'admin_users_show')]
    #[IsGranted('ROLE_ADMIN')]
    public function show(User $user, EntityManagerInterface $em, Request $request): Response
    {
        // --------------------
        // Ajouter une photo
        // --------------------
        if ($request->isMethod('POST') && $request->files->get('photo')) {
            $uploadedFile = $request->files->get('photo');
            $photoName = $request->request->get('photo_name', 'Sans nom'); // Nom personnalisé

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = uniqid() . '_' . $originalFilename . '.' . $uploadedFile->guessExtension();

            try {
                $uploadedFile->move($this->getParameter('photos_directory'), $newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload de la photo.');
                return $this->redirectToRoute('admin_users_show', ['id' => $user->getId()]);
            }

            $photo = new Photo();
            $photo->setName($photoName);           // Utilise le nom du formulaire
            $photo->setImagePath($newFilename);    // Nom du fichier uploadé
            $photo->setUser($user);
            $photo->setCreatedAt(new \DateTime());

            $em->persist($photo);
            $em->flush();

            $this->addFlash('success', 'Photo ajoutée avec succès !');
            return $this->redirectToRoute('admin_users_show', ['id' => $user->getId()]);
        }

        // --------------------
        // Supprimer une photo
        // --------------------
        if ($request->query->get('delete_photo_id')) {
            $photoId = $request->query->get('delete_photo_id');
            $photo = $em->getRepository(Photo::class)->find($photoId);

            if ($photo) {
                $filePath = $this->getParameter('photos_directory') . '/' . $photo->getImagePath();
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $em->remove($photo);
                $em->flush();

                $this->addFlash('success', 'Photo supprimée avec succès !');
            } else {
                $this->addFlash('error', 'Photo introuvable.');
            }

            return $this->redirectToRoute('admin_users_show', ['id' => $user->getId()]);
        }

        // --------------------
        // Récupérer les photos existantes
        // --------------------
        $photos = $em->getRepository(Photo::class)->findBy(['user' => $user]);

        return $this->render('profil_show/index.html.twig', [
            'user' => $user,
            'photos' => $photos,
        ]);
    }
}
