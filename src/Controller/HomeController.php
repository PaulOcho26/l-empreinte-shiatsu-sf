<?php
namespace App\Controller;

use App\Repository\TreatmentRepository;
use App\Repository\ArticleRepository;
use App\Repository\LexiconRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController {
    #[Route('/', name: 'app_home')]
    public function index(TreatmentRepository $repo): Response {
        return $this->render('home/index.html.twig', ['treatments' => $repo->findBy(['isActive' => true])]);
    }

#[Route('/recherche', name: 'app_search')]
public function search(Request $request, ArticleRepository $artRepo, LexiconRepository $lexRepo): Response {
    $q = $request->query->get('q');
    // On cherche dans les articles et le lexique
    return $this->render('search/index.html.twig', [
        'articles' => $artRepo->createQueryBuilder('a')->where('a.title LIKE :q')->setParameter('q', "%$q%")->getQuery()->getResult(),
        'lexicons' => $lexRepo->createQueryBuilder('l')->where('l.term LIKE :q')->setParameter('q', "%$q%")->getQuery()->getResult(),
        'query' => $q
        ]);
    }
}