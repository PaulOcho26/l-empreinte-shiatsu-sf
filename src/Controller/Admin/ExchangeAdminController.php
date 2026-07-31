<?php

namespace App\Controller\Admin;

use App\Repository\TherapeuticExchangeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/echanges')]
class ExchangeAdminController extends AbstractController
{
    #[Route('', name: 'app_admin_exchange_index')]
    public function index(TherapeuticExchangeRepository $repository): Response
    {
        // On récupère les échanges du plus récent au plus ancien
        $echanges = $repository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/exchange_admin/index.html.twig', [
            'echanges' => $echanges,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_exchange_show', methods: ['GET', 'POST'])]
    public function show(Request $request, TherapeuticExchange $echange, EntityManagerInterface $em): Response
    {
        // Si Sandrine envoie une réponse via un formulaire simple
        if ($request->isMethod('POST')) {
            $response = $request->request->get('reply');
            $echange->setPractitionerResponse($response);
            $echange->setStatus('TRAITÉ');
            
            $em->flush();
            $this->addFlash('success', 'Votre réponse a été envoyée au patient.');
            return $this->redirectToRoute('app_admin_exchange_index');
        }

        return $this->render('admin/exchange_admin/show.html.twig', [
            'echange' => $echange,
        ]);
    }
}