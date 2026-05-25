<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_service')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/services/{slug}', name: 'app_service_show')]
    public function show(string $slug, ServiceRepository $serviceRepository): Response
    {
        // On cherche le service en base de données par son "slug"
        $service = $serviceRepository->findOneBy(['slug' => $slug]);

        // Si le service n'est pas trouvé (mauvaise URL), on génère une page 404
        if (!$service) {
            throw $this->createNotFoundException('Désolé, ce soin n\'existe pas encore.');
        }

        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }
}
