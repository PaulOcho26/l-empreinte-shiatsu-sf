<?php

namespace App\Controller;

use App\Service\LexiconLinker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SanctuaryController extends AbstractController
{
    #[Route('/sanctuaire/le-shiatsu', name: 'app_sanctuary_article')]
    public function index(LexiconLinker $linker): Response
    {
        // Texte issu du site réel de Sandrine (Exemple)
        // Dans src/Controller/SanctuaryController.php

$content = "Le Shiatsu est bien plus qu'une technique de massage ; c'est une discipline énergétique japonaise qui s'appuie sur une vision Holistique de l'être humain. En exerçant des pressions sur des points précis, le praticien permet de libérer le Qi et de rétablir son flux harmonieux à travers les Méridiens. Cette pratique millénaire vise à stimuler les capacités d'auto-guérison du corps, en traitant non seulement le symptôme, mais la racine profonde du déséquilibre.";

        // On utilise notre service pour "linker" les mots automatiquement
        $linkedContent = $linker->linkTerms($content);

        return $this->render('sanctuary/index.html.twig', [
            'content' => $linkedContent,
        ]);
    }
}
