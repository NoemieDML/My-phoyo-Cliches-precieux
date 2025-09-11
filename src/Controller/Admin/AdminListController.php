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

#[Route('/admin/list_user')]
#[IsGranted('ROLE_ADMIN')]
class AdminListController extends AbstractController
{
    #[Route('/admin/list_user', name: 'list_user')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $search = $request->query->get('search', ''); // récupère le terme de recherche

        $qb = $em->createQueryBuilder()
            ->select('u, COUNT(p.id) as photoCount')
            ->from(User::class, 'u')
            ->leftJoin(Photo::class, 'p', Join::WITH, 'p.user = u');

        if ($search) {
            $qb->where('u.email LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        $qb->groupBy('u.id');

        $users = $qb->getQuery()->getResult();

        return $this->render('admin_list/index.html.twig', [
            'users' => $users,
            'search' => $search, // pour pré-remplir le champ recherche
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_users_delete')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès !');
        return $this->redirectToRoute('admin_users_index');
    }

    #[Route('/{id}', name: 'admin_users_show')]
    public function show(User $user): Response
    {
        return $this->render('admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
