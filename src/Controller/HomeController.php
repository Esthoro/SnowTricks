<?php

namespace App\Controller;

use App\Repository\IllustrationRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $trickRepository, IllustrationRepository $illustrationRepository): Response
    {
        $allTricks = $trickRepository->findAll();
        $illustrations = $illustrationRepository->findAll();

        $illustrationsByTrick = [];
        foreach ($illustrations as $illustration) {
            $trickId = $illustration->getTrick()->getId();
            if (!isset($illustrationsByTrick[$trickId])) {
                $illustrationsByTrick[$trickId] = [];
            }
            $illustrationsByTrick[$trickId][] = $illustration->getPath();
        }

        return $this->render('home/index.html.twig', [
            'allTricks' => $allTricks,
            'allIllustrations' => $illustrationsByTrick,
        ]);
    }
}
