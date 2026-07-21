<?php

namespace App\Controller;

use App\Entity\Lexicon;
use App\Form\LexiconType;
use App\Repository\LexiconRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lexicon')]
final class LexiconController extends AbstractController
{
    #[Route(name: 'app_lexicon_index', methods: ['GET'])]
    public function index(LexiconRepository $lexiconRepository): Response
    {
        return $this->render('lexicon/index.html.twig', [
            'lexicons' => $lexiconRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lexicon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lexicon = new Lexicon();
        $form = $this->createForm(LexiconType::class, $lexicon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lexicon);
            $entityManager->flush();

            return $this->redirectToRoute('app_lexicon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lexicon/new.html.twig', [
            'lexicon' => $lexicon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lexicon_show', methods: ['GET'])]
    public function show(Lexicon $lexicon): Response
    {
        return $this->render('lexicon/show.html.twig', [
            'lexicon' => $lexicon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lexicon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lexicon $lexicon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LexiconType::class, $lexicon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lexicon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lexicon/edit.html.twig', [
            'lexicon' => $lexicon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lexicon_delete', methods: ['POST'])]
    public function delete(Request $request, Lexicon $lexicon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lexicon->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lexicon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lexicon_index', [], Response::HTTP_SEE_OTHER);
    }
}
