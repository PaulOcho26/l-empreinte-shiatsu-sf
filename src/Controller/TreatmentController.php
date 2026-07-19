<?php

namespace App\Controller;

use App\Repository\TreatmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TreatmentController extends AbstractController
{
    #[Route('/soins', name: 'app_treatment_index')] // On change /treatment par /soins pour l'élégance
    public function index(TreatmentRepository $treatmentRepository): Response
    {
        // On récupère uniquement les soins actifs, classés par prix
        $soins = $treatmentRepository->findBy(['isActive' => true], ['price' => 'ASC']);

        return $this->render('treatment/index.html.twig', [
            'soins' => $soins,
        ]);
    }
}
