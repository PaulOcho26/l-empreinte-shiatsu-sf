<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Service\LexiconLinker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sanctuaire')]
class SanctuaryController extends AbstractController
{
    #[Route('', name: 'app_sanctuary_index')]
    public function index(ArticleRepository $repo, LexiconLinker $linker): Response 
    {
        // Le texte pilier du cabinet
        $introText = "Le Shiatsu est bien plus qu'une technique de massage ; c'est une discipline énergétique japonaise qui s'appuie sur une vision Holistique de l'être humain. En exerçant des pressions sur des points précis, le praticien permet de libérer le Qi et de rétablir son flux harmonieux à travers les Méridiens. Cette pratique millénaire vise à stimuler les capacités d'auto-guérison du corps, en traitant non seulement le symptôme, mais la racine profonde du déséquilibre.";

        return $this->render('sanctuary/index.html.twig', [
            'articles' => $repo->findBy(['isPublished' => true]),
            'introLinked' => $linker->linkTerms($introText) 
        ]);
    }

    #[Route('/{slug}', name: 'app_sanctuary_show')]
    public function show(string $slug, ArticleRepository $repo, LexiconLinker $linker): Response {
    $article = $repo->findOneBy(['slug' => $slug]);
    if (!$article) throw $this->createNotFoundException();
    
    return $this->render('sanctuary/show.html.twig', [
        'article' => $article,
        'content' => $linker->linkTerms($article->getContent()), // Active les liens Lexique
    ]);
}
}