<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AppointmentRepository;
use App\Repository\TherapeuticExchangeRepository; // INDISPENSABLE
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mon-espace')]
#[IsGranted('ROLE_USER')]
final class MemberController extends AbstractController
{
    #[Route('', name: 'app_member_dashboard')]
    public function index(
        AppointmentRepository $appointmentRepository,
        TherapeuticExchangeRepository $exchangeRepository // On appelle le dépôt des messages
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        // 1. On récupère les rendez-vous
        $appointments = $appointmentRepository->findBy(['patient' => $user], ['dateTime' => 'ASC']);

        // 2. On récupère les messages (Synchronicité)
        $exchanges = $exchangeRepository->findBy(['patient' => $user], ['createdAt' => 'DESC']);

        return $this->render('member/index.html.twig', [
            'user' => $user,
            'appointments' => $appointments,
            'exchanges' => $exchanges, // On envoie les messages à la page
        ]);
    }
}