<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\LexiconRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_search')]
    public function index(Request $request, LexiconRepository $lexiconRepo, ArticleRepository $articleRepo): Response
    {
        // On récupère le mot tapé dans l'URL (ex: ?q=qi)
        $query = $request->query->get('q', '');
        
        $resultsLexicon = [];
        $resultsArticles = [];

        if (!empty($query)) {
            $resultsLexicon = $lexiconRepo->findBySearch($query);
            $resultsArticles = $articleRepo->findBySearch($query);
        }

        return $this->render('search/index.html.twig', [
            'query' => $query,
                'lexicon' => $resultsLexicon,
            'articles' => $resultsArticles,
        ]);
    }
}