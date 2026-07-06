<?php

namespace App\Controller;

use App\Repository\TreatmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TreatmentRepository $treatmentRepository): Response
    {
        // On récupère uniquement les soins actifs pour la page d'accueil
        $treatments = $treatmentRepository->findBy(['isActive' => true]);

        return $this->render('home/index.html.twig', [
            'treatments' => $treatments,
        ]);
    }
}