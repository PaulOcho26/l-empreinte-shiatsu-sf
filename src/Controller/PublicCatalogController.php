<?php

namespace App\Controller;

use App\Repository\TreatmentRepository;
use App\Repository\LexiconRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicCatalogController extends AbstractController
{
    #[Route('/soins', name: 'app_soins_public')]
    public function listSoins(TreatmentRepository $repo): Response
    {
        return $this->render('treatment/public_list.html.twig', [
            'soins' => $repo->findAll(),
        ]);
    }

    #[Route('/lexique', name: 'app_lexique_public')]
    public function listLexique(LexiconRepository $repo): Response
    {
        return $this->render('lexicon/public_list.html.twig', [
            'terms' => $repo->findAll(),
        ]);
    }
}