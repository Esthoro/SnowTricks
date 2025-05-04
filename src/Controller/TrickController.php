<?php

namespace App\Controller;

use App\Entity\Illustration;
use App\Entity\Trick;
use App\Repository\IllustrationRepository;
use App\Repository\MessageRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;

final class TrickController extends AbstractController
{
    #[Route('/trick/{slug}', name: 'app_trick_show')]
    public function show(
        string $slug,
        TrickRepository $trickRepository,
        IllustrationRepository $illustrationRepository,
        VideoRepository $videoRepository,
        MessageRepository $messageRepository
    ): Response {
        $trick = $trickRepository->findOneBySlug($slug);

        if (!$trick) {
            throw $this->createNotFoundException('Le trick n\'existe pas.');
        }

        $allIllustrations = $illustrationRepository->findBy(['trick' => $trick]);
        $firstIllustration = !empty($allIllustrations) ? $allIllustrations[0]->getPath() : null;
        $allVideos = $videoRepository->findBy(['trick' => $trick]);
        $messages = $messageRepository->findBy(['trick' => $trick], ['createdAt' => 'DESC']);
        $author = $trick->getUser()->getUserIdentifier();

        // Récupérer les informations de l'auteur pour chaque message
        foreach ($messages as $message) {
            $messageAuthor = $message->getAuthor();
            $message->authorName = $messageAuthor->getName();
            $message->authorPhoto = $messageAuthor->getPhotoPath();
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'allIllustrations' => $allIllustrations,
            'firstIllustration' => $firstIllustration,
            'allVideos' => $allVideos,
            'messages' => $messages,
            'author' => $author,
        ]);
    }

    #[Route('/trick/create', name: 'app_trick_create')]
    public function create(Request $request, EntityManagerInterface $em, Security $security, SluggerInterface $slugger): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (empty($trick->getName()) || empty($trick->getDescription()) || empty($trick->getGroupName())) {
                $this->addFlash('error', 'Le nom de la figure est requis.');
                return $this->redirectToRoute('app_trick_create');
            }

            if (empty($trick->getDescription())) {
                $this->addFlash('error', 'La description de la figure est requise.');
                return $this->redirectToRoute('app_trick_create');
            }

            if (empty($trick->getGroupName())) {
                $this->addFlash('error', 'Le groupe de la figure est requis.');
                return $this->redirectToRoute('app_trick_create');
            }

            $existingTrick = $em->getRepository(Trick::class)->findOneBy(['name' => $trick->getName()]);
            if ($existingTrick) {
                $this->addFlash('error', 'Cette figure existe déjà.');
                return $this->redirectToRoute('app_trick_create');
            }

            $trick->setUser($security->getUser());
            $trick->setCreatedAt(new \DateTimeImmutable());
            $trick->setUpdatedAt(new \DateTime());

            $slug = $slugger->slug($trick->getName())->lower();
            $trick->setSlug($slug);

            // Gérer les illustrations
            $images = $form->get('images')->getData() ?? [];

            if (count($images) === 0) {
                // Image par défaut
                $defaultIllustration = new Illustration();
                $defaultIllustration->setPath(Trick::DEFAULT_ILLUSTRATION_PICTURE);
                $defaultIllustration->setTrick($trick);
                $trick->addIllustration($defaultIllustration);
            } else {
                foreach ($images as $uploadedFile) {
                    if ($uploadedFile instanceof UploadedFile) {
                        $newFilename = uniqid().'.'.$uploadedFile->guessExtension();

                        $uploadedFile->move(
                            $this->getParameter('illustrations_directory'),
                            $newFilename
                        );

                        $illustration = new Illustration();
                        $illustration->setPath($newFilename);
                        $illustration->setTrick($trick);

                        $trick->addIllustration($illustration);
                    }
                }
            }

            $em->persist($trick);
            $em->flush();

            $this->addFlash('success', 'Figure enregistrée avec succès !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('trick/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trick/{id}/delete', name: 'app_trick_delete', methods: ['POST'])]
    public function delete(Trick $trick, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em->remove($trick);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/trick/{id}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, TrickRepository $trickRepository, IllustrationRepository $illustrationRepository): Response {
        $trick = $trickRepository->find($id);

        if (!$trick) {
            throw $this->createNotFoundException('Le trick demandé n\'existe pas.');
        }

        $firstIllustration = $illustrationRepository->findFirstByTrickId($id);

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'first_illustration' => $firstIllustration,
            'illustrations' => $trick->getIllustrations(),
            'videos' => $trick->getVideos(),
        ]);
    }

    #[Route('/trick/update', name: 'app_trick_update', methods: ['POST'])]
    public function update(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $trickId = $request->request->get('trickId');
        $trickName = trim($request->request->get('trickName', ''));
        $trickDescription = trim($request->request->get('trickDescription', ''));
        $groupeTrick = trim($request->request->get('groupeTrickSelect', ''));

        if (!$trickId) {
            return new JsonResponse(['success' => false, 'message' => 'Le\'ID de la figure est requis.']);
        }

        if (empty($trickName)) {
            return new JsonResponse(['success' => false, 'message' => 'Le nom de la figure est requis.']);
        }

        if (empty($trickDescription)) {
            return new JsonResponse(['success' => false, 'message' => 'La description de la figure est requise.']);}

        if (empty($groupeTrick)) {
            return new JsonResponse(['success' => false, 'message' => 'Le groupe de la figure est requis.']);
        }

        $existingTrick = $em->getRepository(Trick::class)->findOneBy(['name' => $trickName]);
        if ($existingTrick && $existingTrick->getId() !== (int) $trickId) {
            return new JsonResponse(['success' => false, 'message' => 'Ce nom est déjà utilisé par un autre trick.']);
        }

        $trick = $em->getRepository(Trick::class)->find($trickId);

        if (!$trick) {
            return new JsonResponse(['success' => false, 'message' => 'Figure non trouvée.']);
        }

        $trick->setName($trickName);
        $trick->setDescription($trickDescription);
        $trick->setGroupName($groupeTrick);

        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}
