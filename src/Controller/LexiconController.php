<?php

namespace App\Controller;

use App\Repository\LexiconRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LexiconController extends AbstractController
{
    #[Route('/lexique', name: 'app_lexicon_index')]
    public function index(LexiconRepository $lexiconRepository): Response
    {
        // On récupère tous les termes triés par ordre alphabétique
        $terms = $lexiconRepository->findBy([], ['term' => 'ASC']);

        return $this->render('lexicon/index.html.twig', [
            'terms' => $terms,
        ]);
    }
}
