<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    // Injection du service EmailVerifier
    public function __construct(private EmailVerifier $emailVerifier) {}

    // Route pour l'inscription d'un utilisateur
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User(); // Création d'un nouvel utilisateur
        $form = $this->createForm(RegistrationFormType::class, $user); // Création du formulaire
        $form->handleRequest($request); // Gestion de la soumission du formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Encode le mot de passe
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Sauvegarde en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi d'un email de confirmation
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('noemie.demol63@gmail.com', 'My Photo Cliches Precieux'))
                    ->to($user->getEmail())
                    ->subject('Confirme ton email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('Accueil'); // Redirection après inscription
        }

        // Affichage du formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    // Route pour vérifier l'email de l'utilisateur
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); // Vérifie que l'utilisateur est connecté

        // Validation du lien de confirmation et mise à jour de l'utilisateur
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }

        // Message de succès et redirection
        $this->addFlash('success', 'Votre adresse email a été vérifiée avec succès !');
        return $this->redirectToRoute('Accueil');
    }
}
