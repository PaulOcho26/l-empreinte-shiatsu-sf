<?php

namespace App\Controller\Admin;

use App\Entity\TherapeuticExchange;
use App\Repository\TherapeuticExchangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/echanges')]
class ExchangeAdminController extends AbstractController
{
    #[Route('', name: 'app_admin_exchange_index')]
    public function index(TherapeuticExchangeRepository $repository): Response
    {
        $echanges = $repository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/exchange_admin/index.html.twig', [
            'echanges' => $echanges,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_exchange_show', methods: ['GET', 'POST'])]
    public function show(Request $request, TherapeuticExchange $echange, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $responseText = $request->request->get('reply');
            $echange->setPractitionerResponse($responseText);
            $echange->setStatus('TRAITÉ');

            $em->flush();
            $this->addFlash('success', 'Votre réponse a été transmise au patient.');

            return $this->redirectToRoute('app_admin_exchange_index');
        }

        return $this->render('admin/exchange_admin/show.html.twig', [
            'echange' => $echange,
        ]);
    }
}