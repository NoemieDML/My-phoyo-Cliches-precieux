<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ContactController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $httpClient
    ) {}

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Création d'un nouvel objet Contact
        $contact = new Contact();

        // Création du formulaire
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde en base
            $em->persist($contact);
            $em->flush();

            $data = $form->getData();
            $to = "noemie.demol63@gmail.com"; // Destinataire
            $subject = "Nouveau message de " . $data->getFirstName() . " " . $data->getLastName();

            try {
                $response = $this->httpClient->request('POST', 'https://api.brevo.com/v3/smtp/email', [
                    'headers' => [
                        'api-key' => getenv('API_BREVO_KEY'),
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'sender' => [
                            'email' => "noemie.demol63@gmail.com", // Email validé dans Brevo
                            'name' => "Mon Site"
                        ],
                        'to' => [
                            [
                                'email' => $to,
                                'name' => 'Destinataire'
                            ]
                        ],
                        'subject' => $subject,
                        'htmlContent' => "
                            <p>Nouveau message de {$data->getFirstName()} {$data->getLastName()}</p>
                            <p>Email : {$data->getEmail()}</p>
                            <p>Message : {$data->getContent()}</p>
                        ",
                    ],
                ]);

                $status = $response->getStatusCode();
                if (in_array($status, [200, 201])) {
                    $this->addFlash('success', 'Message envoyé et enregistré en base !');
                } else {
                    $responseContent = $response->getContent(false); // Récupère le contenu même si erreur
                    $this->addFlash('danger', 'Erreur lors de l’envoi du mail. Réponse API : ' . $responseContent);
                }
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur lors de l’envoi : ' . $e->getMessage());
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
