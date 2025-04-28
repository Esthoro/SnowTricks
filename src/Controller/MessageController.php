<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageController extends AbstractController
{
    #[Route('/message/create', name: 'app_message_create', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function addMessage(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        ?UserInterface $user
    ): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['error' => 'Requête invalide.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $trickId = $request->request->get('id');
        $content = $request->request->get('message');

        if (empty($trickId)) {
            return new JsonResponse(['error' => 'ID de la figure manquant.'], JsonResponse::HTTP_BAD_REQUEST);
        }
        if (empty($content)) {
            return new JsonResponse(['error' => 'Contenu du message manquant.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $trick = $em->getRepository(Trick::class)->find($trickId);

        if (!$trick) {
            return new JsonResponse(['error' => 'La figure n\'existe pas.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $comment = (new Message())
            ->setContent($content)
            ->setTrick($trick)
            ->setAuthor($user)
            ->setCreatedAt(new \DateTimeImmutable());

        $errors = $validator->validate($comment);

        if (count($errors) > 0) {
            $errorMessages = array_map(static fn($e) => $e->getMessage(), iterator_to_array($errors));
            return new JsonResponse(['error' => implode(' ', $errorMessages)], JsonResponse::HTTP_BAD_REQUEST);
        }

        $em->persist($comment);
        $em->flush();

        return new JsonResponse(['success' => 'Message ajouté avec succès !'], JsonResponse::HTTP_OK);
    }
}
