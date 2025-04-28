<?php

namespace App\Controller;

use App\Entity\Illustration;
use App\Repository\IllustrationRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IllustrationController extends AbstractController
{
    #[Route('/illustration/update', name: 'app_illustration_update', methods: ['POST'])]
    public function updateIllustration(
        Request $request,
        IllustrationRepository $illustrationRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $illustrationId = $request->request->get('idIllustrationToUpdate');
        $illustration = $illustrationRepository->find($illustrationId);

        if (!$illustration) {
            return new JsonResponse(['error' => 'Illustration introuvable.'], 404);
        }

        /** @var UploadedFile $file */
        $file = $request->files->get('illustration');

        if (!$file) {
            return new JsonResponse(['error' => 'Aucun fichier reçu.'], 400);
        }

        $newFilename = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getParameter('illustrations_directory'), $newFilename);
        } catch (FileException $e) {
            return new JsonResponse(['error' => 'Erreur lors de l\'upload du fichier.'], 500);
        }

        $oldFilePath = $this->getParameter('illustrations_directory') . '/' . $illustration->getPath();
        if (file_exists($oldFilePath) && is_file($oldFilePath)) {
            unlink($oldFilePath);
        }

        $illustration->setPath($newFilename);

        $entityManager->flush();

        return new JsonResponse(['success' => 'Illustration mise à jour avec succès !']);
    }

    #[Route('/illustration/delete', name: 'app_illustration_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $illustrationId = $request->request->get('id');

        if (!$illustrationId) {
            return new JsonResponse(['error' => 'ID de l\'illustration manquant.'], 400);
        }

        $illustration = $em->getRepository(Illustration::class)->find($illustrationId);

        if (!$illustration) {
            return new JsonResponse(['error' => 'Illustration non trouvée.'], 404);
        }

        $imagePath = $this->getParameter('illustrations_directory') . '/' . $illustration->getPath();
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $em->remove($illustration);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

}

