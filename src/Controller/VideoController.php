<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoController extends AbstractController
{
    #[Route('/video/update', name: 'app_video_update', methods: ['POST'])]
    public function updateVideo(
        Request $request,
        VideoRepository $videoRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {

        $videoId = $request->request->get('id');
        $video = $videoRepository->find($videoId);

        if (!$video) {
            return new JsonResponse(['error' => 'Video introuvable.'], 404);
        }

        try {
            $video->setEmbedCode($request->request->get('video'));
        } catch (FileException $e) {
            return new JsonResponse(['error' => 'Erreur lors de la mise à jour du code.'], 500);
        }

        $entityManager->flush();

        return new JsonResponse(['success' => 'Vidéo mise à jour avec succès !']);
    }

    #[Route('/video/delete', name: 'app_video_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $videoId = $request->request->get('id');

        if (!$videoId) {
            return new JsonResponse(['error' => 'ID de la vidéo manquant.'], 400);
        }

        $video = $em->getRepository(Video::class)->find($videoId);

        if (!$video) {
            return new JsonResponse(['error' => 'Vidéo non trouvée.'], 404);
        }

        try {
            $trick = $video->getTrick();
            if ($trick) {
                $trick->getVideos()->removeElement($video);
            }

            $em->remove($video);
            $em->flush();

            return new JsonResponse(['success' => 'Vidéo supprimée avec succès.']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la suppression de la vidéo: ' . $e->getMessage()], 500);
        }
    }
}

