<?php

namespace App\Controller;

use App\Entity\Contact; // Entité représentant un message de contact
use App\Form\ContactType; // Formulaire lié à l’entité Contact
use Doctrine\ORM\EntityManagerInterface; // Gestion de la base de données
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    // Route qui gère le formulaire de contact
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Création d’un nouvel objet Contact
        $contact = new Contact();

        // Génération du formulaire lié à l’objet Contact
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request); // Vérifie si le formulaire est soumis

        // Vérifie si le formulaire est envoyé et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement des données en base
            $em->persist($contact);
            $em->flush();

            // Message flash pour confirmer l'enregistrement
            $this->addFlash('success', 'Votre message à bien étais envoyer');

            if ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('error', 'Le message n’a pas pu être envoyé. Veuillez vérifier le formulaire.');
            }



            // Redirection vers la page contact après soumission
            return $this->redirectToRoute('app_contact');
        }

        // Affichage du formulaire dans la vue Twig
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
