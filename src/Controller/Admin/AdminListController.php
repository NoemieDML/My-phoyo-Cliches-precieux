<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;

// Définition du préfixe de route pour ce controller et restriction aux admins
#[Route('/admin/list_user')]
#[IsGranted('ROLE_ADMIN')]
class AdminListController extends AbstractController
{
    // Route pour lister les utilisateurs avec recherche
    #[Route('/admin/list_user', name: 'list_user')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $search = $request->query->get('search', '');
        // Récupère le terme de recherche depuis l'URL (?search=...)

        $qb = $em->createQueryBuilder()
            ->select('u, COUNT(p.id) as photoCount')
            // Sélectionne l'utilisateur et le nombre de photos liées
            ->from(User::class, 'u')
            ->leftJoin(Photo::class, 'p', Join::WITH, 'p.user = u');
        // Jointure gauche pour récupérer toutes les photos associées (ou 0)

        if ($search) {

            $qb->where('u.email LIKE :search')
                ->setParameter('search', '%' . $search . '%');
            // Si un terme de recherche est fourni, filtre par email
        }

        $qb->groupBy('u.id');
        // Regroupe par utilisateur pour utiliser COUNT

        $users = $qb->getQuery()->getResult();
        // Exécute la requête et récupère les résultats

        return $this->render('admin_list/index.html.twig', [
            'users' => $users,
            'search' => $search, // Pré-remplit le champ de recherche dans le template
        ]);
    }

    // Route pour supprimer un utilisateur et ses photos
    #[Route('/delete/{id}', name: 'admin_users_delete')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        // Récupère toutes les photos associées à l'utilisateur
        $photos = $em->getRepository(Photo::class)->findBy(['user' => $user]);

        foreach ($photos as $photo) {
            $em->remove($photo); // Supprime chaque photo individuellement
        }

        // Supprime ensuite l'utilisateur
        $em->remove($user);
        $em->flush(); // Applique toutes les suppressions en base de données

        $this->addFlash('success', 'Utilisateur supprimé avec succès !');
        // Message flash pour la confirmation
        return $this->redirectToRoute('list_user');
        // Redirection vers la liste des utilisateurs


    }

    // Route pour afficher le détail d'un utilisateur
    #[Route('/{id}', name: 'admin_users_show')]
    public function show(User $user): Response
    {
        return $this->render('admin_user/show.html.twig', [
            'user' => $user, // Passe l'utilisateur au template
        ]);
    }
}
