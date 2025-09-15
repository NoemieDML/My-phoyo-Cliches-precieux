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
    // Page pour afficher le profil d'un utilisateur et gérer ses photos (admin seulement)
    #[Route('/show/{id}', name: 'admin_users_show')]
    #[IsGranted('ROLE_ADMIN')]
    public function show(User $user, EntityManagerInterface $em, Request $request): Response
    {
        // Ajouter une photo
        if ($request->isMethod('POST') && $request->files->get('photo')) {
            $uploadedFile = $request->files->get('photo'); // Récupère le fichier uploadé
            $photoName = $request->request->get('photo_name', 'Sans nom'); // Nom personnalisé

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = uniqid() . '_' . $originalFilename . '.' . $uploadedFile->guessExtension();

            try {
                // Déplace le fichier dans le dossier défini
                $uploadedFile->move($this->getParameter('photos_directory'), $newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload de la photo.');
                return $this->redirectToRoute('admin_users_show', ['id' => $user->getId()]);
            }

            // Création de l'entité Photo
            $photo = new Photo();
            $photo->setName($photoName);           // Nom personnalisé
            $photo->setImagePath($newFilename);    // Nom du fichier
            $photo->setUser($user);                // Associe à l'utilisateur
            $photo->setCreatedAt(new \DateTime()); // Date actuelle

            $em->persist($photo); // Prépare l'enregistrement
            $em->flush();         // Enregistre en base

            $this->addFlash('success', 'Photo ajoutée avec succès !');
            return $this->redirectToRoute('admin_users_show', ['id' => $user->getId()]);
        }

        // Supprimer une photo
        if ($request->query->get('delete_photo_id')) {
            $photoId = $request->query->get('delete_photo_id'); // ID de la photo à supprimer
            $photo = $em->getRepository(Photo::class)->find($photoId);

            if ($photo) {
                $filePath = $this->getParameter('photos_directory') . '/' . $photo->getImagePath();
                if (file_exists($filePath)) {
                    unlink($filePath); // Supprime le fichier physique
                }

                $em->remove($photo); // Supprime l'entité
                $em->flush();

                $this->addFlash('success', 'Photo supprimée avec succès !');
            } else {
                $this->addFlash('error', 'Photo introuvable.');
            }

            return $this->redirectToRoute('admin_users_show', ['id' => $user->getId()]);
        }

        // Récupérer les photos existantes
        $photos = $em->getRepository(Photo::class)->findBy(['user' => $user]);

        // Affiche le profil et les photos dans Twig
        return $this->render('profil_show/index.html.twig', [
            'user' => $user,
            'photos' => $photos,
        ]);
    }
}
