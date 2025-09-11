<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminContactController extends AbstractController
{
    #[Route('/admin/contacts', name: 'admin_contacts')]
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();

        return $this->render('admin_contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/admin/contact/delete/{id}', name: 'admin_contact_delete', methods: ['POST'])]
    public function delete(Contact $contact, EntityManagerInterface $em): Response
    {
        $em->remove($contact);
        $em->flush();

        $this->addFlash('success', 'Le message a été supprimé.');

        return $this->redirectToRoute('admin_contacts');
    }
}
