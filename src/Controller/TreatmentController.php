<?php

namespace App\Controller;

use App\Entity\Treatment;
use App\Form\TreatmentType;
use App\Repository\TreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/treatment')]
final class TreatmentController extends AbstractController
{
    #[Route(name: 'app_treatment_index', methods: ['GET'])]
    public function index(TreatmentRepository $treatmentRepository): Response
    {
        return $this->render('treatment/index.html.twig', [
            'treatments' => $treatmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_treatment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $treatment = new Treatment();
        $form = $this->createForm(TreatmentType::class, $treatment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($treatment);
            $entityManager->flush();

            return $this->redirectToRoute('app_treatment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('treatment/new.html.twig', [
            'treatment' => $treatment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_treatment_show', methods: ['GET'])]
    public function show(Treatment $treatment): Response
    {
        return $this->render('treatment/show.html.twig', [
            'treatment' => $treatment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_treatment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Treatment $treatment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TreatmentType::class, $treatment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_treatment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('treatment/edit.html.twig', [
            'treatment' => $treatment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_treatment_delete', methods: ['POST'])]
    public function delete(Request $request, Treatment $treatment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$treatment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($treatment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_treatment_index', [], Response::HTTP_SEE_OTHER);
    }
}
