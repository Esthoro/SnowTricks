<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset-password', name: 'app_forgot_password_request')]
    public function request(Request $request, UserRepository $userRepository, EntityManagerInterface $em, \Symfony\Component\Mailer\MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                $token = Uuid::v4()->toRfc4122();
                $user->setConfirmationToken($token);
                $user->setTokenRequestedAt(new \DateTimeImmutable());
                $em->flush();

                $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                    ->from('no-reply@snowtricks.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->html($this->renderView(
                        'emails/reset_password.html.twig',
                        ['resetUrl' => $resetUrl]
                    ));

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    $this->addFlash('error', 'L\'email de réinitialisation n\'a pas pu être envoyé. Veuillez réessayer.');
                    return $this->redirectToRoute('app_forgot_password_request');
                }
            }

            $this->addFlash('success', 'Si un compte existe avec cette adresse, un email a été envoyé.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(string $token, Request $request, UserRepository $userRepository, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(ResetPasswordFormType::class, null, [
            'token' => $token,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['confirmationToken' => $token]);

            if (!$user || $user->getTokenRequestedAt() < new \DateTimeImmutable('-2 hours')) {
                $this->addFlash('danger', 'Le lien est invalide ou a expiré.');
                return $this->redirectToRoute('app_forgot_password_request');
            }

            $password = $form->get('password')->getData();
            $hashedPassword = $hasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $user->setConfirmationToken(null);
            $user->setTokenRequestedAt(null);
            $em->flush();

            $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('reset_password/reset.html.twig', [
            'token' => $token,
            'resetPasswordForm' => $form->createView(),
        ]);
    }

}
