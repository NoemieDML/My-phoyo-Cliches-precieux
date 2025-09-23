<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier) {}

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        UserAuthenticatorInterface $userAuthenticator,
        AppAuthenticator $authenticator
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        $formErrors = [];

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $plainPassword = $form->get('plainPassword')->getData();
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                $entityManager->persist($user);
                $entityManager->flush();

                // Connexion automatique avant l'envoi d'email
                $response = $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );

                // Envoi d'email de confirmation après la connexion
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('noemie.demol63@gmail.com', 'My Photo Cliches Precieux'))
                        ->to($user->getEmail())
                        ->subject('Confirme ton email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );

                return $response;
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $formErrors[] = $error->getMessage();
                }
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'form_errors' => $formErrors,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash(
                'verify_email_error',
                $translator->trans($exception->getReason(), [], 'VerifyEmailBundle')
            );

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse email a été vérifiée avec succès !');
        return $this->redirectToRoute('Accueil');
    }
}
