<?php

namespace App\Controller;

use App\Repository\TreatmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicTreatmentController extends AbstractController
{
    #[Route('/soins', name: 'app_treatment_index')]
    public function index(TreatmentRepository $repo): Response
    {
        return $this->render('treatment/index.html.twig', [
            'treatments' => $repo->findBy(['isActive' => true]),
        ]);
    }

    #[Route('/soins/{slug}', name: 'app_treatment_show')]
    public function show(string $slug, TreatmentRepository $repo): Response
    {
        $treatment = $repo->findOneBy(['slug' => $slug]);
        if (!$treatment) throw $this->createNotFoundException();

        return $this->render('treatment/show.html.twig', [
            'treatment' => $treatment,
        ]);
    }
}