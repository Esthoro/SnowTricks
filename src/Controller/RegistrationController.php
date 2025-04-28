<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Uid\Uuid;

class RegistrationController extends AbstractController
{

    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        PasswordHasherFactoryInterface $passwordHasherFactory,
        SluggerInterface $slugger,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hasher = $passwordHasherFactory->getPasswordHasher($user);
            $hashedPassword = $hasher->hash($form->get('password')->getData());
            $user->setPassword($hashedPassword);

            // Gestion de la photo de profil
            $photoFile = $form->get('photoPath')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                // Déplacer le fichier
                $photoFile->move(
                    $this->getParameter('profile_pictures_directory'),
                    $newFilename
                );

                $user->setPhotoPath($newFilename);
            } else {
                $user->setPhotoPath(User::DEFAULT_PROFILE_PICTURE);
            }

            // Définir l'utilisateur comme non vérifié
            $user->setStatus(false);

            try {
                $entityManager->persist($user);
                $entityManager->flush();

                // Générer le token pour l'email de vérification
                $token = Uuid::v4()->toRfc4122();
                $user->setConfirmationToken($token);
                $user->setTokenRequestedAt(new \DateTimeImmutable());
                $entityManager->flush();

                $confirmUrl = $this->generateUrl('app_confirm', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // Envoi de l'email
                $email = (new Email())
                    ->from('no-reply@snowtricks.com')
                    ->to($user->getEmail())
                    ->subject('Confirmez votre inscription')
                    ->html($this->renderView(
                        'emails/confirmation.html.twig',
                        ['confirmUrl' => $confirmUrl]
                    ));

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    $this->addFlash('error', 'L\'email de confirmation n\'a pas pu être envoyé. Veuillez réessayer.');
                    return $this->redirectToRoute('app_register');
                }

                $this->addFlash('success', 'Inscription réussie ! Vérifiez vos emails pour activer votre compte.');

                return $this->redirectToRoute('app_home');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'L\'email est déjà utilisé. Veuillez en choisir un autre.');
            }

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/confirm/{token}', name: 'app_confirm')]
    public function confirmEmail(
        string $token,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Token invalide');
        } else if ($user->getTokenRequestedAt() < new \DateTimeImmutable('-2 hours')) {
            $this->addFlash('danger', 'Le lien est invalide ou a expiré.');
            return $this->redirectToRoute('app_register');
        }

        $user->setStatus(true);
        $user->setConfirmationToken(null);
        $user->setTokenRequestedAt(null);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été activé. Vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('app_login');
    }
}