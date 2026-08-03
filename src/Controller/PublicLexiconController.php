<?php
namespace App\Controller;

use App\Repository\LexiconRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicLexiconController extends AbstractController
{
    #[Route('/lexique', name: 'app_lexicon_index')]
    public function index(LexiconRepository $repo): Response
    {
        return $this->render('lexicon/public_index.html.twig', [
            'lexicons' => $repo->findAll(),
        ]);
    }

    #[Route('/lexique/{slug}', name: 'app_lexicon_show')]
    public function show(string $slug, LexiconRepository $repo): Response
    {
        $lexicon = $repo->findOneBy(['slug' => $slug]);
        if (!$lexicon) throw $this->createNotFoundException();
        return $this->render('lexicon/show.html.twig', ['lexicon' => $lexicon]);
    }
}